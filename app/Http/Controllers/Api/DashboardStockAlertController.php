<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class DashboardStockAlertController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $baseQuery = Product::query()->whereColumn('stock', '<', 'min_stock');

        $lowStockCount = (clone $baseQuery)->count();

        $lowStockProducts = (clone $baseQuery)
            ->select(['id', 'name', 'stock', 'min_stock'])
            ->orderBy('stock')
            ->orderBy('name')
            ->take(5)
            ->get()
            ->map(function (Product $product): array {
                return [
                    'id' => (int) $product->id,
                    'name' => $product->name,
                    'stock' => (int) $product->stock,
                    'min_stock' => (int) ($product->min_stock ?? 20),
                    'status' => 'Stok Hampir Habis',
                ];
            })
            ->values();

        return response()->json([
            'low_stock_count' => $lowStockCount,
            'low_stock_products' => $lowStockProducts,
        ]);
    }
}
