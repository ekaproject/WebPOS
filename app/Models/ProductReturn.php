<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    protected $fillable = [
        'distributor_id', 'reference', 'return_date', 'note',
    ];

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnItem::class);
    }
}
