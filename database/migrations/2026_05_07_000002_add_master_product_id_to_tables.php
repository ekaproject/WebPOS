<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah master_product_id ke inbound_items
        Schema::table('inbound_items', function (Blueprint $table) {
            $table->foreignId('master_product_id')
                ->nullable()
                ->after('distributor_id')
                ->constrained('master_products')
                ->nullOnDelete();
        });

        // Tambah master_product_id ke products
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('master_product_id')
                ->nullable()
                ->after('category_id')
                ->constrained('master_products')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('master_product_id');
        });

        Schema::table('inbound_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('master_product_id');
        });
    }
};
