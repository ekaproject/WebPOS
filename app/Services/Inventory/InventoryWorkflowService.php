<?php

namespace App\Services\Inventory;

use App\Models\Category;
use App\Models\InboundItem;
use App\Models\InventoryReturn;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InventoryWorkflowService
{
    public function processQc(InboundItem $inboundItem, int $goodQty, int $damagedQty, ?int $checkedBy = null): InboundItem
    {
        if ($goodQty < 0 || $damagedQty < 0) {
            throw ValidationException::withMessages([
                'good_qty' => 'Jumlah barang baik dan rusak tidak boleh negatif.',
            ]);
        }

        if ($inboundItem->qc_status === 'completed' || $inboundItem->qcItem()->exists()) {
            throw ValidationException::withMessages([
                'qc_status' => 'QC untuk barang masuk ini sudah diproses sebelumnya.',
            ]);
        }

        if (($goodQty + $damagedQty) !== (int) $inboundItem->quantity_inbound) {
            throw ValidationException::withMessages([
                'good_qty' => 'Total barang baik dan rusak harus sama dengan jumlah barang masuk.',
            ]);
        }

        DB::transaction(function () use ($inboundItem, $goodQty, $damagedQty, $checkedBy) {
            $inboundItem->qcItem()->create([
                'good_qty' => $goodQty,
                'damaged_qty' => $damagedQty,
                'checked_at' => now(),
                'checked_by' => $checkedBy,
            ]);

            if ($damagedQty > 0) {
                $inboundItem->returns()->create([
                    'distributor_id' => $inboundItem->distributor_id,
                    'product_name' => $inboundItem->product_name,
                    'qty' => $damagedQty,
                    'status' => 'pending',
                ]);
            }

            if ($goodQty > 0) {
                $this->createProductBatch(
                    name: $inboundItem->product_name,
                    stock: $goodQty,
                    expiredDate: $inboundItem->expired_date->toDateString(),
                    distributorId: $inboundItem->distributor_id,
                    sourceType: 'qc',
                    sourceReferenceId: $inboundItem->id,
                    inboundItemId: $inboundItem->id,
                    returnId: null,
                    categoryId: $inboundItem->category_id,
                    productImage: $inboundItem->product_photo,
                    purchasePrice: (float) $inboundItem->purchase_price,
                    sellingPrice: (float) $inboundItem->selling_price,
                    unit: $inboundItem->ukuran_produk
                );
            }

            $inboundItem->update(['qc_status' => 'completed']);
        });

        return $inboundItem->fresh(['distributor', 'qcItem', 'returns', 'products']);
    }

    public function completeReturn(InventoryReturn $inventoryReturn, int $replacementQty, string $replacementExpiredDate): InventoryReturn
    {
        if ($inventoryReturn->status !== 'pending') {
            throw ValidationException::withMessages([
                'status' => 'Retur ini sudah diselesaikan.',
            ]);
        }

        if ($replacementQty <= 0) {
            throw ValidationException::withMessages([
                'replacement_qty' => 'Jumlah barang pengganti harus lebih dari 0.',
            ]);
        }

        DB::transaction(function () use ($inventoryReturn, $replacementQty, $replacementExpiredDate) {
            $inventoryReturn->loadMissing('inboundItem');

            $inventoryReturn->update([
                'status' => 'completed',
                'resolved_at' => now(),
            ]);

            $this->createProductBatch(
                name: $inventoryReturn->product_name,
                stock: $replacementQty,
                expiredDate: $replacementExpiredDate,
                distributorId: $inventoryReturn->distributor_id,
                sourceType: 'return_replacement',
                sourceReferenceId: $inventoryReturn->id,
                inboundItemId: $inventoryReturn->inbound_item_id,
                returnId: $inventoryReturn->id,
                categoryId: $inventoryReturn->inboundItem?->category_id,
                productImage: $inventoryReturn->inboundItem?->product_photo,
                purchasePrice: (float) ($inventoryReturn->inboundItem?->purchase_price ?? 0),
                sellingPrice: (float) ($inventoryReturn->inboundItem?->selling_price ?? 0),
                unit: $inventoryReturn->inboundItem?->ukuran_produk
            );
        });

        return $inventoryReturn->fresh(['distributor', 'inboundItem', 'replacementProducts']);
    }

    private function createProductBatch(
        string $name,
        int $stock,
        string $expiredDate,
        int $distributorId,
        string $sourceType,
        int $sourceReferenceId,
        ?int $inboundItemId,
        ?int $returnId,
        ?int $categoryId = null,
        ?string $productImage = null,
        ?float $purchasePrice = 0,
        ?float $sellingPrice = 0,
        ?string $unit = null
    ): Product {
        return Product::create([
            'name' => $name,
            'sku' => $this->generateBatchSku($name),
            'category_id' => $categoryId ?? $this->resolveFallbackCategoryId(),
            'distributor_id' => $distributorId,
            'purchase_price' => $purchasePrice ?? 0,
            'price' => $sellingPrice ?? 0,
            'stock' => $stock,
            'min_stock' => 0,
            'unit' => filled($unit) ? $unit : 'pcs',
            'description' => 'Batch otomatis dari proses QC/retur distributor.',
            'image' => $productImage,
            'is_active' => true,
            'source_type' => $sourceType,
            'source_reference_id' => $sourceReferenceId,
            'inbound_item_id' => $inboundItemId,
            'return_id' => $returnId,
            'expires_at' => $expiredDate,
        ]);
    }

    private function resolveFallbackCategoryId(): int
    {
        $category = Category::query()
            ->visibleForMenu()
            ->where('is_active', true)
            ->orderBy('name')
            ->first();

        if (!$category) {
            $category = Category::query()
                ->visibleForMenu()
                ->orderBy('name')
                ->first();
        }

        if (!$category) {
            $category = Category::query()->firstOrCreate(
                ['slug' => 'umum'],
                [
                    'name' => 'Umum',
                    'icon' => 'category',
                    'description' => 'Kategori fallback otomatis.',
                    'type' => 'fmcg',
                    'is_active' => true,
                ]
            );
        }

        return (int) $category->id;
    }

    private function generateBatchSku(string $productName): string
    {
        $sanitized = Str::slug($productName, '');
        $prefix = Str::upper(Str::substr($sanitized, 0, 3));
        $prefix = str_pad($prefix ?: 'PRD', 3, 'X');

        do {
            $sku = sprintf('%s-%s-%s', $prefix, now()->format('ymdHis'), Str::upper(Str::random(3)));
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }
}
