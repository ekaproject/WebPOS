<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        if (! Schema::hasColumn('products', 'kode_produk')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('kode_produk')->nullable()->unique()->after('sku');
            });
        }

        // Isi barcode lama supaya semua produk langsung bisa diprint setelah migrate.
        DB::table('products')
            ->whereNull('kode_produk')
            ->orderBy('id')
            ->get(['id'])
            ->each(function ($product) {
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['kode_produk' => sprintf('PRD-%06d', $product->id)]);
            });
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'kode_produk')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique(['kode_produk']);
                $table->dropColumn('kode_produk');
            });
        }
    }
};