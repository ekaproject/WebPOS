<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Promo;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

        $promos = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        $products = Product::with('category')->where('is_active', true)->orderBy('name')->get();

        return view('admin.promos.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|distinct|exists:products,id',
            'products.*.type' => 'required|in:fixed,percent',
            'products.*.discount_value' => 'required|numeric|min:0.01',
            'products.*.start_date' => 'required|date',
            'products.*.end_date' => 'required|date',
            'is_active' => 'boolean',
        ], [
            'products.required' => 'Tambahkan minimal satu produk promo.',
            'products.min' => 'Tambahkan minimal satu produk promo.',
            'products.*.product_id.required' => 'Produk wajib dipilih.',
            'products.*.product_id.distinct' => 'Produk tidak boleh dipilih lebih dari satu kali.',
            'products.*.type.required' => 'Jenis promo wajib dipilih.',
            'products.*.discount_value.required' => 'Nilai diskon wajib diisi.',
            'products.*.discount_value.min' => 'Nilai diskon minimal 0.01.',
            'products.*.start_date.required' => 'Tanggal mulai wajib diisi.',
            'products.*.end_date.required' => 'Tanggal selesai wajib diisi.',
        ]);

        $validator->after(function ($validator) use ($request) {
            foreach ((array) $request->input('products', []) as $index => $promoProduct) {
                $startDate = $promoProduct['start_date'] ?? null;
                $endDate = $promoProduct['end_date'] ?? null;

                if ($startDate && $endDate && $endDate < $startDate) {
                    $validator->errors()->add(
                        "products.$index.end_date",
                        'Tanggal selesai harus sama atau setelah tanggal mulai.'
                    );
                }

                if (($promoProduct['type'] ?? null) === 'percent' && (float) ($promoProduct['discount_value'] ?? 0) > 100) {
                    $validator->errors()->add(
                        "products.$index.discount_value",
                        'Diskon persen maksimal 100%.'
                    );
                }

                $discountError = $this->validateDiscountNotBelowCost([
                    'type' => $promoProduct['type'] ?? null,
                    'discount_value' => $promoProduct['discount_value'] ?? null,
                    'product_id' => $promoProduct['product_id'] ?? null,
                ]);

                if ($discountError) {
                    $validator->errors()->add("products.$index.discount_value", $discountError);
                }
            }
        });

        $validated = $validator->validate();
        $isActive = $request->boolean('is_active', true);

        $selectedProducts = Product::query()
            ->whereIn('id', collect($validated['products'])->pluck('product_id')->unique()->values())
            ->get()
            ->keyBy('id');

        DB::transaction(function () use ($validated, $selectedProducts, $isActive) {
            foreach ($validated['products'] as $promoProduct) {
                $product = $selectedProducts->get((int) $promoProduct['product_id']);
                if (!$product) {
                    continue;
                }

                Promo::create([
                    'title' => 'Promo '.$product->name,
                    'description' => null,
                    'type' => $promoProduct['type'],
                    'discount_value' => $promoProduct['discount_value'],
                    'min_purchase' => null,
                    'voucher_code' => null,
                    'voucher_quota' => null,
                    'voucher_claimed' => 0,
                    'category_id' => $product->category_id,
                    'product_id' => $product->id,
                    'start_date' => $promoProduct['start_date'],
                    'end_date' => $promoProduct['end_date'],
                    'is_active' => $isActive,
                ]);
            }
        });

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo multi-produk berhasil ditambahkan.');
    }

    public function edit(Promo $promo)
    {
        $categories = Category::query()->visibleForMenu()->orderBy('name')->get();
        $products = Product::with('category')->where('is_active', true)->orderBy('name')->get();

        return view('admin.promos.edit', compact('promo', 'categories', 'products'));
    }

    public function update(Request $request, Promo $promo)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'voucher_quota' => 'nullable|integer|min:1|max:100000',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'required|exists:products,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        if ((string) $data['type'] === 'percent' && (float) $data['discount_value'] > 100) {
            return back()->withInput()->withErrors([
                'discount_value' => 'Diskon persen maksimal 100%.',
            ]);
        }

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
        $minPurchase = $data['min_purchase'] ?? null;
        $isVoucher = !is_null($minPurchase) && (float) $minPurchase > 0;

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

        $discountType = (string) ($data['type'] ?? 'fixed');
        $discountValue = (float) $data['discount_value'];
        $purchasePrice = (float) ($product->purchase_price ?? 0);
        $sellingPrice = (float) $product->price;
        $minAllowedPrice = max($purchasePrice, 0);

        if ($discountType === 'percent') {
            if ($discountValue > 100) {
                return 'Diskon persen maksimal 100%.';
            }

            $discountedPrice = $sellingPrice - ($sellingPrice * ($discountValue / 100));

            if ($discountedPrice < $minAllowedPrice) {
                $maxPercent = $sellingPrice > 0
                    ? max((($sellingPrice - $minAllowedPrice) / $sellingPrice) * 100, 0)
                    : 0;

                return 'Diskon persen terlalu besar. Harga setelah diskon (Rp '.number_format($discountedPrice, 0, ',', '.').') tidak boleh lebih kecil dari harga beli (Rp '.number_format($purchasePrice, 0, ',', '.').'). Maksimal diskon aman untuk produk ini adalah '.number_format($maxPercent, 2, ',', '.').'%.';
            }

            return null;
        }

        $discountedPrice = $sellingPrice - $discountValue;

        if ($discountedPrice < $minAllowedPrice) {
            $maxDiscount = max($sellingPrice - $minAllowedPrice, 0);
            return 'Potongan terlalu besar. Harga setelah diskon (Rp '.number_format($discountedPrice, 0, ',', '.').') tidak boleh lebih kecil dari harga beli (Rp '.number_format($purchasePrice, 0, ',', '.').'). Maksimal potongan untuk produk ini adalah Rp '.number_format($maxDiscount, 0, ',', '.').'.';
        }

        return null;
    }
}
