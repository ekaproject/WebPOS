<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'title', 'description', 'type', 'discount_value',
        'min_purchase', 'category_id', 'product_id', 'start_date', 'end_date',
        'voucher_code', 'voucher_quota', 'voucher_claimed',
        'image', 'is_active',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'is_active'   => 'boolean',
        'voucher_quota' => 'integer',
        'voucher_claimed' => 'integer',
        'discount_value' => 'decimal:2',
        'min_purchase'   => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function claims()
    {
        return $this->hasMany(PromoClaim::class);
    }

    public function isActive(): bool
    {
        $today = now()->toDateString();
        return $this->is_active
            && $this->start_date->toDateString() <= $today
            && $this->end_date->toDateString() >= $today;
    }

    public function getDiscountLabelAttribute(): string
    {
        return match($this->type) {
            'percent'   => $this->discount_value . '%',
            'fixed'     => 'Rp ' . number_format($this->discount_value, 0, ',', '.'),
            'free_item' => 'Gratis Item',
            default     => '-',
        };
    }

    public function getVoucherRemainingAttribute(): ?int
    {
        if (is_null($this->voucher_quota)) {
            return null;
        }

        return max(0, $this->voucher_quota - $this->voucher_claimed);
    }
}
