<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'ukuran',
        'satuan_id',
        'category_id',
        'unit',
        'price',
        'description',
        'image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    /**
     * Label lengkap produk: "Susu UHT 250ml (pcs)"
     */
    public function getFullNameAttribute(): string
    {
        $parts = [$this->name];
        if ($this->ukuran) {
            $parts[] = $this->ukuran;
        }
        if ($this->satuan) {
            $parts[] = "({$this->satuan->singkatan})";
        }
        return implode(' ', $parts);
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
