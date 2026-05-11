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
        $products = Product::with(['category', 'masterProduct'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get()
            ->map(fn(Product $p) => $this->formatProduct($p));

        return response()->json(['data' => $products]);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::with(['category', 'masterProduct'])->findOrFail($id);

        return response()->json(['data' => $this->formatProduct($product)]);
    }

    public function categories(): JsonResponse
    {
        $categories = Category::select('id', 'name')->get();

        return response()->json(['data' => $categories]);
    }

    private function formatProduct(Product $product): array
    {
        // Prioritas gambar: 1) product.image, 2) masterProduct.image
        $imagePath = $product->image ?? $product->masterProduct?->image;

        return [
            'id'         => $product->id,
            'name'       => $product->name,
            'sku'        => $product->sku,
            'price'      => (int) $product->price,
            'stock'      => (int) $product->stock,
            'min_stock'  => (int) $product->min_stock,
            'unit'       => $product->unit ?? 'pcs',
            'category'   => $product->category?->name,
            'image_url'  => $imagePath
                ? asset('storage/' . $imagePath)
                : null,
            'expires_at' => $product->expires_at?->format('d/m/Y'),
        ];
    }
}
