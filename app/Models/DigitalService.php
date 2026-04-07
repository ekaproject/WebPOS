<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalService extends Model
{
    protected $fillable = [
        'name', 'slug', 'icon', 'description',
        'provider', 'base_price', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'base_price' => 'decimal:2',
    ];
}
