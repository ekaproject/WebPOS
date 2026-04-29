<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distributor;
use App\Models\InventoryReturn;
use App\Services\Inventory\InventoryWorkflowService;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    // Legacy API: keep endpoint for existing integrations.
    public function getProductsByDistributor($id)
    {
        $distributor = Distributor::with(['products' => function ($q) {
            $q->where('is_active', true);
        }])->findOrFail($id);

        $products = $distributor->products->map(function ($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'purchase_price' => $p->pivot->purchase_price,
            ];
        });

        return response()->json(['products' => $products]);
    }

    public function index(Request $request)
    {
        $status = in_array($request->status, ['pending', 'confirmed', 'completed'], true)
            ? $request->status
            : 'pending';

        $query = InventoryReturn::query()
            ->with(['distributor', 'inboundItem'])
            ->latest();

        if ($status === 'pending') {
            $query->whereIn('status', ['pending', 'confirmed']);
        } else {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('product_name', 'like', '%' . $keyword . '%')
                    ->orWhereHas('distributor', function ($dq) use ($keyword) {
                        $dq->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $returns = $query->paginate(20);

        return view('admin.returns.index', compact('returns', 'status'));
    }

    public function show(InventoryReturn $inventoryReturn)
    {
        $inventoryReturn->load(['distributor', 'inboundItem', 'replacementProducts']);

        return view('admin.returns.show', compact('inventoryReturn'));
    }

    public function complete(
        Request $request,
        InventoryReturn $inventoryReturn,
        InventoryWorkflowService $workflowService
    ) {
        $data = $request->validate([
            'replacement_qty' => 'required|integer|min:1',
            'replacement_expired_date' => 'required|date',
        ]);

        $workflowService->completeReturn(
            inventoryReturn: $inventoryReturn,
            replacementQty: (int) $data['replacement_qty'],
            replacementExpiredDate: $data['replacement_expired_date']
        );

        return redirect()
            ->route('admin.returns.show', $inventoryReturn)
            ->with('success', 'Retur selesai. Barang pengganti disimpan sebagai batch produk baru dan langsung aktif.');
    }
}
