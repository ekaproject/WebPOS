<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class Product extends Model
{
    use HasFactory;

    protected $attributes = [
        'min_stock' => 20,
    ];

    protected $fillable = [
        'name', 'sku', 'kode_produk', 'category_id', 'master_product_id', 'distributor_id', 'purchase_price', 'price', 'stock',
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
        'stock_display',
        'image_url',
        'barcode_svg',
        'barcode_value',
    ];

    protected static function booted(): void
    {
        static::created(function (Product $product) {
            if (filled($product->kode_produk)) {
                return;
            }

            if (filled($product->master_product_id)) {
                $masterBarcode = $product->masterProduct()->value('barcode');

                if (filled($masterBarcode)) {
                    // Stok produk mengikuti barcode master agar konsisten di seluruh alur.
                    $product->forceFill([
                        'kode_produk' => $masterBarcode,
                    ])->saveQuietly();

                    return;
                }
            }

            // Fallback untuk produk yang dibuat manual tanpa master product.
            $product->forceFill([
                'kode_produk' => sprintf('PRD-%06d', $product->id),
            ])->saveQuietly();
        });
    }

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

    public function masterProduct()
    {
        return $this->belongsTo(MasterProduct::class);
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

    public function getStockDisplayAttribute(): string
    {
        return trim(((string) ($this->stock ?? 0)) . ' ' . ($this->unit ?: 'pcs'));
    }

    public function getImageUrlAttribute(): ?string
    {
        if (filled($this->image) && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        $masterImage = $this->masterProduct?->image;

        if (filled($masterImage) && file_exists(public_path('storage/' . $masterImage))) {
            return asset('storage/' . $masterImage);
        }

        return null;
    }

    public function getBarcodeSvgAttribute(): string
    {
        $barcodeValue = $this->barcode_value;

        if (! filled($barcodeValue)) {
            return '';
        }

        return DNS1D::getBarcodeSVG($barcodeValue, 'C128', 2, 60);
    }

    public function getBarcodeValueAttribute(): string
    {
        return $this->masterProduct?->barcode ?: $this->getRawOriginal('kode_produk') ?: $this->getRawOriginal('sku') ?: '-';
    }

    public function getNameAttribute($value): string
    {
        if (filled($this->master_product_id)) {
            if ($this->relationLoaded('masterProduct') && $this->masterProduct) {
                return $this->masterProduct->name;
            }

            $masterName = $this->masterProduct()->value('name');

            if (filled($masterName)) {
                return $masterName;
            }
        }

        return (string) $value;
    }

    public function getKodeProdukAttribute($value): string
    {
        if (filled($this->master_product_id)) {
            if ($this->relationLoaded('masterProduct') && $this->masterProduct) {
                return $this->masterProduct->barcode_value;
            }

            $masterBarcode = $this->masterProduct()->value('barcode');

            if (filled($masterBarcode)) {
                return $masterBarcode;
            }
        }

        return (string) $value;
    }

    public function isLowStock(): bool
    {
        return $this->status_stock === 'low_stock';
    }
}
