<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AjaxSearchController extends Controller
{
    public function products(Request $request)
    {
        $q = (string) $request->query('q', '');

        if (trim($q) === '') {
            return response()->json(['data' => []]);
        }

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($qBuilder) use ($q) {
                $qBuilder->where('name', 'like', '%'.$q.'%')
                    ->orWhere('sku', 'like', '%'.$q.'%');
            })
            ->with('category')
            ->orderByDesc('stock')
            ->take(12)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => (float) $p->price,
                    'image' => $p->image ? asset('storage/'.$p->image) : null,
                    'detail_url' => route('products.show', $p),
                    'unit' => $p->unit,
                ];
            });

        return response()->json(['data' => $products]);
    }
}
