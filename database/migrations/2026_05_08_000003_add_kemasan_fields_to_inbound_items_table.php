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
            // Jenis kemasan saat beli (contoh: dus, karton, eceran)
            if (!Schema::hasColumn('inbound_items', 'kemasan_beli')) {
                $table->string('kemasan_beli', 50)->nullable()->after('ukuran_produk');
            }

            // Berapa isi per kemasan (contoh: 1 dus = 24 pcs, 1 karton = 48 pcs)
            if (!Schema::hasColumn('inbound_items', 'isi_per_kemasan')) {
                $table->unsignedInteger('isi_per_kemasan')->nullable()->after('kemasan_beli');
            }

            // Jumlah kemasan yang dibeli (contoh: beli 2 dus)
            // quantity_inbound akan = jumlah_kemasan * isi_per_kemasan
            if (!Schema::hasColumn('inbound_items', 'jumlah_kemasan')) {
                $table->unsignedInteger('jumlah_kemasan')->nullable()->after('isi_per_kemasan');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::table('inbound_items', function (Blueprint $table) {
            foreach (['jumlah_kemasan', 'isi_per_kemasan', 'kemasan_beli'] as $col) {
                if (Schema::hasColumn('inbound_items', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
