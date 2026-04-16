<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'category_id', 'purchase_price', 'price', 'stock',
        'min_stock', 'unit', 'description', 'image', 'is_active', 'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active'  => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }
}
