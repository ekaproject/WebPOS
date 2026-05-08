<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('master_products') && !Schema::hasColumn('master_products', 'barcode')) {
            Schema::table('master_products', function (Blueprint $table) {
                $table->string('barcode')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('master_products') && Schema::hasColumn('master_products', 'barcode')) {
            Schema::table('master_products', function (Blueprint $table) {
                $table->dropColumn('barcode');
            });
        }
    }
};
