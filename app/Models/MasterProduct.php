<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'unit',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inboundItems()
    {
        return $this->hasMany(InboundItem::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
