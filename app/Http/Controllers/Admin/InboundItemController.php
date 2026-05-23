<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\InboundItem;
use App\Models\MasterProduct;
use App\Models\Satuan;
use App\Services\Inventory\InventoryWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class InboundItemController extends Controller
{
    public function searchMasterProducts(Request $request)
    {
        $keyword = trim((string) $request->query('q', ''));

        $products = MasterProduct::query()
            ->where('is_active', true)
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            })
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name']);

        return response()->json($products);
    }

    public function productDetail(MasterProduct $masterProduct)
    {
        $masterProduct->loadMissing(['category', 'satuan']);

        return response()->json([
            'id' => $masterProduct->id,
            'name' => $masterProduct->name,
            'ukuran' => $masterProduct->ukuran,
            'satuan' => $masterProduct->satuan?->singkatan ?? $masterProduct->unit,
            'category_id' => $masterProduct->category_id,
            'category_name' => $masterProduct->category?->name,
            'price' => $masterProduct->price !== null ? (float) $masterProduct->price : null,
            'image_url' => $masterProduct->image && file_exists(public_path('storage/' . $masterProduct->image)) ? asset('storage/' . $masterProduct->image) : null,
        ]);
    }

    public function index(Request $request)
    {
        $query = InboundItem::query()->with(['distributor', 'qcItem'])->latest();

        if ($request->filled('status') && in_array($request->status, ['pending', 'completed'], true)) {
            $query->where('qc_status', $request->status);
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('product_name', 'like', '%' . $keyword . '%')
                    ->orWhere('ukuran_produk', 'like', '%' . $keyword . '%')
                    ->orWhereHas('distributor', function ($dq) use ($keyword) {
                        $dq->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $inboundItems = $query->paginate(20);

        return view('admin.inbound-items.index', compact('inboundItems'));
    }

    public function create()
    {
        $distributors = Distributor::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $satuans = Satuan::query()
            ->orderBy('nama')
            ->get();

        return view('admin.inbound-items.create', compact('distributors', 'satuans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'distributor_id'    => 'required|exists:distributors,id',
            'master_product_id' => 'required|exists:master_products,id',
            'kemasan_beli'      => 'required|string|max:50',
            'isi_per_kemasan'   => 'required|integer|min:1',
            'jumlah_kemasan'    => 'required|integer|min:1',
            'product_photo'     => 'nullable|image|max:2048',
            'purchase_price'    => 'required|numeric|min:0',
            'inbound_date'      => 'required|date',
            'expired_date'      => 'required|date|after_or_equal:inbound_date',
        ], [
            'master_product_id.required' => 'Produk harus dipilih dari daftar master produk.',
            'master_product_id.exists'   => 'Produk yang dipilih tidak valid.',
        ]);

        // Auto-fill dari master product
        $masterProduct = MasterProduct::query()->with('category')->findOrFail($data['master_product_id']);
        $data['product_name'] = $masterProduct->name;
        $data['ukuran_produk'] = $masterProduct->ukuran;
        $data['category_id'] = $masterProduct->category_id;
        $data['quantity_inbound'] = (int) $data['jumlah_kemasan'] * (int) $data['isi_per_kemasan'];
        $data['selling_price'] = (float) ($masterProduct->price ?? 0);

        // Handle file upload with better error handling
        $photoPath = null;
        try {
            if ($request->hasFile('product_photo')) {
                $file = $request->file('product_photo');
                $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
                $destination = public_path('storage/inbound-products');
                
                // Ensure directory exists with correct permissions
                File::ensureDirectoryExists($destination, 0755, true);
                
                // Move file to destination
                $file->move($destination, $filename);
                $photoPath = 'inbound-products/' . $filename;
                $data['product_photo'] = $photoPath;
            }
        } catch (\Exception $e) {
            Log::error('Inbound product photo upload failed: ' . $e->getMessage(), [
                'file_name' => $file->getClientOriginalName() ?? 'unknown',
                'user_id' => auth()->id(),
            ]);
            return back()
                ->withInput()
                ->withErrors(['product_photo' => 'Gagal menyimpan foto produk: ' . $e->getMessage()]);
        }

        // Save inbound item
        try {
            $inboundItem = InboundItem::create($data);
        } catch (\Exception $e) {
            // Clean up uploaded file if model creation fails
            if ($photoPath && File::exists(public_path('storage/' . $photoPath))) {
                try {
                    File::delete(public_path('storage/' . $photoPath));
                } catch (\Exception $deleteError) {
                    Log::warning('Failed to delete uploaded photo during rollback: ' . $deleteError->getMessage());
                }
            }
            
            Log::error('Inbound item creation failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => array_except($data, ['product_photo']),
            ]);
            return back()
                ->withInput()
                ->withErrors(['general' => 'Gagal menyimpan data barang masuk: ' . $e->getMessage()]);
        }

        return redirect()
            ->route('admin.inbound-items.show', $inboundItem)
            ->with('success', 'Barang masuk berhasil ditambahkan. Silakan lakukan proses QC.');
    }

    public function show(InboundItem $inboundItem)
    {
        $inboundItem->load(['distributor', 'category', 'qcItem', 'returns', 'products']);

        return view('admin.inbound-items.show', compact('inboundItem'));
    }

    public function processQc(Request $request, InboundItem $inboundItem, InventoryWorkflowService $workflowService)
    {
        $data = $request->validate([
            'good_qty' => 'required|integer|min:0',
            'damaged_qty' => 'required|integer|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        $workflowService->processQc(
            inboundItem: $inboundItem,
            goodQty: (int) $data['good_qty'],
            damagedQty: (int) $data['damaged_qty'],
            note: $data['note'] ?? null,
            checkedBy: auth()->id()
        );

        return redirect()
            ->route('admin.inbound-items.show', $inboundItem)
            ->with('success', 'QC berhasil diproses. Produk aktif dan data retur otomatis dibuat sesuai hasil QC.');
    }
}
