<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'description', 'type', 'is_active'];

    public function scopeVisibleForMenu($query)
    {
        return $query->where('slug', '!=', 'inventory-qc');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
