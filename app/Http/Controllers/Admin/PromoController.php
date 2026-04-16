<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Promo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::with(['category', 'product']);

        if ($request->filled('search')) {
            $keyword = trim((string) $request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $query->where('type', 'fixed');

        $promos = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')->where('is_active', true)->orderBy('name')->get();

        return view('admin.promos.create', compact('categories', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'voucher_quota' => 'nullable|integer|min:1|max:100000',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $data['type'] = 'fixed';

        if ($error = $this->validateDiscountNotBelowCost($data)) {
            return back()->withInput()->withErrors(['discount_value' => $error]);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data = $this->prepareVoucherFields($data);

        Promo::create($data);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')->where('is_active', true)->orderBy('name')->get();

        return view('admin.promos.edit', compact('promo', 'categories', 'products'));
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'voucher_quota' => 'nullable|integer|min:1|max:100000',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $data['type'] = 'fixed';

        if ($error = $this->validateDiscountNotBelowCost($data)) {
            return back()->withInput()->withErrors(['discount_value' => $error]);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data = $this->prepareVoucherFields($data, $promo);

        if (!is_null($data['voucher_quota']) && $promo->voucher_claimed > $data['voucher_quota']) {
            return back()->withInput()->withErrors([
                'voucher_quota' => 'Kuota tidak boleh lebih kecil dari jumlah klaim saat ini ('.$promo->voucher_claimed.').',
            ]);
        }

        $promo->update($data);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo berhasil dihapus.');
    }

    private function prepareVoucherFields(array $data, ?Promo $promo = null): array
    {
        $isVoucher = !is_null($data['min_purchase']) && (float) $data['min_purchase'] > 0;

        if ($isVoucher) {
            $data['voucher_quota'] = (int) ($data['voucher_quota'] ?? ($promo?->voucher_quota ?? 10));
            $data['voucher_claimed'] = $promo?->voucher_claimed ?? 0;
            $data['voucher_code'] = $promo?->voucher_code ?: $this->generateVoucherCode();
        } else {
            $data['voucher_quota'] = null;
            $data['voucher_claimed'] = 0;
            $data['voucher_code'] = null;
        }

        return $data;
    }

    private function generateVoucherCode(): string
    {
        do {
            $code = 'VCR-' . Str::upper(Str::random(6));
        } while (Promo::where('voucher_code', $code)->exists());

        return $code;
    }

    private function validateDiscountNotBelowCost(array $data): ?string
    {
        if (empty($data['product_id'])) {
            return null;
        }

        $product = Product::query()->find($data['product_id']);
        if (!$product) {
            return null;
        }

        $discountValue = (float) $data['discount_value'];
        $purchasePrice = (float) ($product->purchase_price ?? 0);
        $sellingPrice = (float) $product->price;
        $minAllowedPrice = max($purchasePrice, 0);
        $discountedPrice = $sellingPrice - $discountValue;

        if ($discountedPrice < $minAllowedPrice) {
            $maxDiscount = max($sellingPrice - $minAllowedPrice, 0);
            return 'Potongan terlalu besar. Harga setelah diskon (Rp '.number_format($discountedPrice, 0, ',', '.').') tidak boleh lebih kecil dari harga beli (Rp '.number_format($purchasePrice, 0, ',', '.').'). Maksimal potongan untuk produk ini adalah Rp '.number_format($maxDiscount, 0, ',', '.').'.';
        }

        return null;
    }
}
