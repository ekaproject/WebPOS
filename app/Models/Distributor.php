<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Distributor extends Model
{
    protected $fillable = [
        'name', 'code', 'contact_person', 'phone', 'email', 'address', 'is_active',
    ];

    protected static function booted(): void
    {
        static::creating(function (Distributor $distributor) {
            if (filled($distributor->code)) {
                return;
            }

            $distributor->code = static::generateCode();
        });
    }

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function generateCode(): string
    {
        do {
            $code = 'DIST-' . Str::upper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'distributor_product')
                    ->withPivot('purchase_price')
                    ->withTimestamps();
    }

    public function inboundItems()
    {
        return $this->hasMany(InboundItem::class);
    }

    public function inventoryReturns()
    {
        return $this->hasMany(InventoryReturn::class);
    }
}
