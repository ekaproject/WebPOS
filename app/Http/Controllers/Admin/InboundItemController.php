<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\InboundItem;
use App\Models\MasterProduct;
use App\Services\Inventory\InventoryWorkflowService;
use Illuminate\Http\Request;

class InboundItemController extends Controller
{
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

        $masterProducts = MasterProduct::query()
            ->with(['category', 'satuan'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.inbound-items.create', compact('distributors', 'masterProducts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'distributor_id'    => 'required|exists:distributors,id',
            'master_product_id' => 'required|exists:master_products,id',
            'ukuran_produk'     => 'required|string|max:100',
            'category_id'       => 'required|exists:categories,id',
            'product_photo'     => 'nullable|image|max:2048',
            'purchase_price'    => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0',
            'quantity_inbound'  => 'required|integer|min:1',
            'inbound_date'      => 'required|date',
            'expired_date'      => 'required|date|after_or_equal:inbound_date',
            'note'              => 'nullable|string|max:1000',
        ], [
            'master_product_id.required' => 'Produk harus dipilih dari daftar master produk.',
            'master_product_id.exists'   => 'Produk yang dipilih tidak valid.',
            'selling_price.gte'          => 'Harga jual tidak boleh lebih rendah dari harga beli.',
        ]);

        // Auto-fill dari master product — tidak perlu diinput ulang di form
        $masterProduct = MasterProduct::findOrFail($data['master_product_id']);
        $data['product_name'] = $masterProduct->name;

        if ($request->hasFile('product_photo')) {
            $file = $request->file('product_photo');
            $filename = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
            $destination = public_path('storage/inbound-products');
            File::ensureDirectoryExists($destination, 0755, true);
            $file->move($destination, $filename);
            $data['product_photo'] = 'inbound-products/' . $filename;
        }

        $inboundItem = InboundItem::create($data);

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
        ]);

        $workflowService->processQc(
            inboundItem: $inboundItem,
            goodQty: (int) $data['good_qty'],
            damagedQty: (int) $data['damaged_qty'],
            checkedBy: auth()->id()
        );

        return redirect()
            ->route('admin.inbound-items.show', $inboundItem)
            ->with('success', 'QC berhasil diproses. Produk aktif dan data retur otomatis dibuat sesuai hasil QC.');
    }
}
