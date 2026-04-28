<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE products MODIFY min_stock INT NOT NULL DEFAULT 20');
        } catch (\Throwable $e) {
            // Ignore when column definition already matches target default.
        }

        try {
            DB::statement('ALTER TABLE products ADD INDEX products_stock_min_stock_index (stock, min_stock)');
        } catch (\Throwable $e) {
            // Ignore when index already exists.
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE products DROP INDEX products_stock_min_stock_index');
        } catch (\Throwable $e) {
            // Ignore when index does not exist.
        }

        try {
            DB::statement('ALTER TABLE products MODIFY min_stock INT NOT NULL DEFAULT 10');
        } catch (\Throwable $e) {
            // Ignore when column definition is different.
        }
    }
};
