<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inbound_items')) {
            Schema::table('inbound_items', function (Blueprint $table) {
                if (!Schema::hasColumn('inbound_items', 'kemasan_beli')) {
                    $table->string('kemasan_beli', 50)->nullable()->after('master_product_id');
                }
                if (!Schema::hasColumn('inbound_items', 'isi_per_kemasan')) {
                    $table->integer('isi_per_kemasan')->nullable()->after('kemasan_beli');
                }
                if (!Schema::hasColumn('inbound_items', 'jumlah_kemasan')) {
                    $table->integer('jumlah_kemasan')->nullable()->after('isi_per_kemasan');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('inbound_items')) {
            Schema::table('inbound_items', function (Blueprint $table) {
                if (Schema::hasColumn('inbound_items', 'kemasan_beli')) $table->dropColumn('kemasan_beli');
                if (Schema::hasColumn('inbound_items', 'isi_per_kemasan')) $table->dropColumn('isi_per_kemasan');
                if (Schema::hasColumn('inbound_items', 'jumlah_kemasan')) $table->dropColumn('jumlah_kemasan');
            });
        }
    }
};
