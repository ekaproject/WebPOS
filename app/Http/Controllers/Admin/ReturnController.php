<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\ReturnItem;
use App\Models\Distributor;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{

    // API: Get products by distributor
    public function getProductsByDistributor($id)
    {
        $distributor = Distributor::with(['products' => function($q) {
            $q->where('is_active', true);
        }])->findOrFail($id);
        $products = $distributor->products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'purchase_price' => $p->pivot->purchase_price,
            ];
        });
        return response()->json(['products' => $products]);
    }
    public function create()
    {
        $distributors = Distributor::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('admin.returns.create', compact('distributors', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'distributor_id' => 'required|exists:distributors,id',
            'return_date' => 'required|date',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|integer|min:0',
            'items.*.note' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $retur = ProductReturn::create([
                'distributor_id' => $data['distributor_id'],
                'return_date' => $data['return_date'],
                'note' => $data['note'] ?? null,
            ]);
            foreach ($data['items'] as $item) {
                ReturnItem::create([
                    'return_id' => $retur->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'note' => $item['note'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.returns.create')->with('success', 'Retur berhasil disimpan!');
    }
}
