<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $fillable = [
        'name', 'code', 'contact_person', 'phone', 'email', 'address', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'distributor_product')
                    ->withPivot('purchase_price')
                    ->withTimestamps();
    }
}
