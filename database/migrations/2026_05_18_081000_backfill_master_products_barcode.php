<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('master_products') || ! Schema::hasColumn('master_products', 'barcode')) {
            return;
        }

        DB::table('master_products')
            ->whereNull('barcode')
            ->orWhere('barcode', '')
            ->orderBy('id')
            ->get(['id'])
            ->each(function ($masterProduct) {
                DB::table('master_products')
                    ->where('id', $masterProduct->id)
                    ->update(['barcode' => sprintf('MPD-%06d', $masterProduct->id)]);
            });
    }

    public function down(): void
    {
        // No-op: data backfill cannot be safely reversed.
    }
};