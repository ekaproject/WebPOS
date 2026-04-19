<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    protected $fillable = [
        'return_id', 'product_id', 'quantity', 'purchase_price', 'note',
    ];

    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    // Deprecated: for compatibility, but should not be used
    public function return()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
