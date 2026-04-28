<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $attributes = [
        'min_stock' => 20,
    ];

    protected $fillable = [
        'name', 'sku', 'category_id', 'distributor_id', 'purchase_price', 'price', 'stock',
        'min_stock', 'unit', 'description', 'image', 'is_active', 'source_type', 'source_reference_id',
        'inbound_item_id', 'return_id', 'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active'  => 'boolean',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    protected $appends = [
        'status_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function distributors()
    {
        return $this->belongsToMany(Distributor::class, 'distributor_product')
                    ->withPivot('purchase_price')
                    ->withTimestamps();
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function inboundItem()
    {
        return $this->belongsTo(InboundItem::class);
    }

    public function inventoryReturn()
    {
        return $this->belongsTo(InventoryReturn::class, 'return_id');
    }

    public function getStatusStockAttribute(): string
    {
        $minimumStock = $this->min_stock ?? 20;

        return $this->stock < $minimumStock ? 'low_stock' : 'safe';
    }

    public function isLowStock(): bool
    {
        return $this->status_stock === 'low_stock';
    }
}
