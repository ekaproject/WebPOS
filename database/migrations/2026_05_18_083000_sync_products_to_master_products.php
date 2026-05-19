<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
// use Throwable;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products') || ! Schema::hasTable('master_products')) {
            return;
        }

        if (Schema::hasColumn('products', 'kode_produk')) {
            try {
                Schema::table('products', function ($table) {
                    $table->dropUnique('products_kode_produk_unique');
                });
            } catch (Throwable $e) {
                // Ignore if the unique index name differs on this database.
            }
        }

        $products = DB::table('products')
            ->leftJoin('master_products', 'products.master_product_id', '=', 'master_products.id')
            ->whereNotNull('products.master_product_id')
            ->select([
                'products.id',
                'master_products.name as master_name',
                'master_products.barcode as master_barcode',
                'master_products.unit as master_unit',
            ])
            ->orderBy('products.id')
            ->get();

        foreach ($products as $product) {
            DB::table('products')
                ->where('id', $product->id)
                ->update([
                    'name' => $product->master_name,
                    'kode_produk' => $product->master_barcode,
                    'unit' => $product->master_unit,
                ]);
        }
    }

    public function down(): void
    {
        // No safe automatic rollback for data sync.
    }
};
