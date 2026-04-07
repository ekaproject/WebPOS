<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\PromoClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller
{
    public function index(Request $request)
    {
        $query = Promo::with(['category', 'product.category'])
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

        if ($request->filled('type') && in_array($request->type, ['percent', 'fixed', 'free_item'], true)) {
            $query->where('type', $request->type);
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

    public function claim(Request $request)
    {
        $validated = $request->validate([
            'voucher_code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($validated['voucher_code']));
        $userId = (int) auth()->id();

        $message = DB::transaction(function () use ($code, $userId) {
            $promo = Promo::where('voucher_code', $code)
                ->whereNotNull('min_purchase')
                ->where('is_active', true)
                ->whereDate('start_date', '<=', now()->toDateString())
                ->whereDate('end_date', '>=', now()->toDateString())
                ->lockForUpdate()
                ->first();

            if (!$promo) {
                return [
                    'type' => 'error',
                    'text' => 'Kode voucher tidak ditemukan atau sudah tidak aktif.',
                ];
            }

            if (is_null($promo->voucher_quota) || $promo->voucher_remaining <= 0) {
                return [
                    'type' => 'error',
                    'text' => 'Kuota voucher sudah habis.',
                ];
            }

            $alreadyClaimed = PromoClaim::where('promo_id', $promo->id)
                ->where('user_id', $userId)
                ->exists();

            if ($alreadyClaimed) {
                return [
                    'type' => 'error',
                    'text' => 'Voucher ini sudah pernah Anda klaim.',
                ];
            }

            PromoClaim::create([
                'promo_id' => $promo->id,
                'user_id' => $userId,
            ]);
            $promo->increment('voucher_claimed');

            return [
                'type' => 'success',
                'text' => 'Voucher berhasil diklaim. Gunakan kode '.$promo->voucher_code.' untuk min. belanja Rp '.number_format((float) $promo->min_purchase, 0, ',', '.').' dan benefit '.$promo->discount_label.'.',
            ];
        });

        return back()->with($message['type'], $message['text']);
    }
}
