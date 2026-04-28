<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::table('inbound_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inbound_items', 'ukuran_produk')) {
                $table->string('ukuran_produk', 100)->nullable()->after('product_name');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::table('inbound_items', function (Blueprint $table) {
            if (Schema::hasColumn('inbound_items', 'ukuran_produk')) {
                $table->dropColumn('ukuran_produk');
            }
        });
    }
};
