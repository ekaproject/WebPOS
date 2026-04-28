<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'inbound_item_id',
        'distributor_id',
        'product_name',
        'qty',
        'status',
        'resolved_at',
        'note',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function inboundItem()
    {
        return $this->belongsTo(InboundItem::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function replacementProducts()
    {
        return $this->hasMany(Product::class, 'return_id');
    }
}
