<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Promo;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class MobileProductController extends Controller
{
    public function index(): JsonResponse
    {
        $today = now()->toDateString();

        $activePromos = Promo::where('is_active', true)
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->whereNull('voucher_code')
            ->whereNull('min_purchase')
            ->whereIn('type', ['percent', 'fixed'])
            ->get();

        // Product-specific promos take priority over category-wide promos
        $promoByProduct  = $activePromos->whereNotNull('product_id')
            ->groupBy('product_id')->map->first();
        $promoByCategory = $activePromos->whereNull('product_id')
            ->whereNotNull('category_id')
            ->groupBy('category_id')->map->first();

        $products = Product::with(['category', 'masterProduct'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get()
            ->map(fn(Product $p) => $this->formatProduct($p, $promoByProduct, $promoByCategory));

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

    private function formatProduct(Product $product, ?Collection $promoByProduct = null, ?Collection $promoByCategory = null): array
    {
        $imagePath = $product->image ?? $product->masterProduct?->image;

        // Resolve active promo: product-specific first, then category-wide
        $promo = null;
        if ($promoByProduct?->has($product->id)) {
            $promo = $promoByProduct->get($product->id);
        } elseif ($product->category_id && $promoByCategory?->has($product->category_id)) {
            $promo = $promoByCategory->get($product->category_id);
        }

        $promoData = null;
        if ($promo) {
            $value = (float) $promo->discount_value;
            $price = (float) $product->price;
            $discountedPrice = $promo->type === 'percent'
                ? (int) round($price * (1 - $value / 100))
                : (int) max(0, $price - $value);

            $label = $promo->type === 'percent'
                ? number_format($value, 0) . '% OFF'
                : 'Rp ' . number_format($value, 0, ',', '.') . ' OFF';

            $promoData = [
                'type'             => $promo->type,
                'discount_value'   => $value,
                'label'            => $label,
                'discounted_price' => $discountedPrice,
            ];
        }

        return [
            'id'         => $product->id,
            'name'       => $product->name,
            'sku'        => $product->sku,
            'barcode'    => $product->masterProduct?->barcode,
            'price'      => (int) $product->price,
            'stock'      => (int) $product->stock,
            'min_stock'  => (int) $product->min_stock,
            'unit'       => $product->unit ?? 'pcs',
            'ukuran'     => $product->masterProduct?->ukuran,
            'satuan'     => $product->masterProduct?->satuan?->singkatan,
            'category'   => $product->category?->name,
            'image_url'  => ($imagePath && file_exists(public_path('storage/' . $imagePath)))
                ? asset('storage/' . $imagePath)
                : null,
            'expires_at' => $product->expires_at?->format('d/m/Y'),
            'promo'      => $promoData,
        ];
    }
}
