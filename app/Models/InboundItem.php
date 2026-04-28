<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributor_id',
        'product_name',
        'ukuran_produk',
        'category_id',
        'product_photo',
        'quantity_inbound',
        'inbound_date',
        'expired_date',
        'qc_status',
        'note',
        'purchase_price',
        'selling_price',
    ];

    protected $casts = [
        'inbound_date' => 'date',
        'expired_date' => 'date',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function qcItem()
    {
        return $this->hasOne(QcItem::class);
    }

    public function returns()
    {
        return $this->hasMany(InventoryReturn::class, 'inbound_item_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
