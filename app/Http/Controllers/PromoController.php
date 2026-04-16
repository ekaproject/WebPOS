<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::with(['category', 'product.category'])
            ->where('type', 'fixed')
            ->where('is_active', true)
            ->whereDate('end_date', '>=', now()->toDateString());

        if ($request->filled('search')) {
            $keyword = trim((string) $request->search);

            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%')
                    ->orWhereHas('category', function ($categoryQuery) use ($keyword) {
                        $categoryQuery->where('name', 'like', '%'.$keyword.'%');
                    })
                    ->orWhereHas('product', function ($productQuery) use ($keyword) {
                        $productQuery->where('name', 'like', '%'.$keyword.'%');
                    });
            });
        }

        $promos = $query->orderBy('start_date')->orderByDesc('discount_value')->get();

        $dailyPromos = $promos->filter(function ($promo) {
            return is_null($promo->min_purchase);
        })->values();

        $voucherPromos = $promos->filter(function ($promo) {
            return !is_null($promo->min_purchase);
        })->values();

        $productPromos = $promos->filter(function ($promo) {
            return !is_null($promo->product_id) && !is_null($promo->product);
        })->values();

        return view('promos', compact('promos', 'dailyPromos', 'voucherPromos', 'productPromos'));
    }
}
