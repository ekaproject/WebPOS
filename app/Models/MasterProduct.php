<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

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

    protected $appends = [
        'barcode_svg',
        'barcode_value',
    ];

    protected static function booted(): void
    {
        static::created(function (MasterProduct $masterProduct) {
            if (filled($masterProduct->barcode)) {
                return;
            }

            // Barcode master produk dihasilkan dari ID agar stabil dan unik.
            $masterProduct->forceFill([
                'barcode' => sprintf('MPD-%06d', $masterProduct->id),
            ])->saveQuietly();
        });
    }

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

    public function getBarcodeValueAttribute(): string
    {
        return $this->barcode ?: sprintf('MPD-%06d', $this->id);
    }

    public function getBarcodeSvgAttribute(): string
    {
        $barcodeValue = $this->barcode_value;

        if (! filled($barcodeValue)) {
            return '';
        }

        return DNS1D::getBarcodeSVG($barcodeValue, 'C128', 2, 60);
    }
}
