<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class MobileProductController extends Controller
{
    public function index(): JsonResponse
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get()
            ->map(fn(Product $p) => $this->formatProduct($p));

        return response()->json(['data' => $products]);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json(['data' => $this->formatProduct($product)]);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::select('id', 'name')->get();

        return response()->json(['data' => $categories]);
    }

    private function formatProduct(Product $product): array
    {
        return [
            'id'         => $product->id,
            'name'       => $product->name,
            'sku'        => $product->sku,
            'price'      => (int) $product->price,
            'stock'      => (int) $product->stock,
            'category'   => $product->category?->name,
            'image_url'  => $product->image
                ? asset('storage/' . $product->image)
                : null,
            'expires_at' => $product->expires_at?->format('d/m/Y'),
        ];
    }
}
