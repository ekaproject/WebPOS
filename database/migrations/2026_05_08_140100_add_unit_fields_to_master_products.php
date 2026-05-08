<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('master_products')) {
            Schema::table('master_products', function (Blueprint $table) {
                if (!Schema::hasColumn('master_products', 'unit_small')) {
                    $table->string('unit_small')->default('pcs')->after('unit');
                }
                if (!Schema::hasColumn('master_products', 'unit_large')) {
                    $table->string('unit_large')->nullable()->after('unit_small');
                }
                if (!Schema::hasColumn('master_products', 'conversion_qty')) {
                    $table->integer('conversion_qty')->default(1)->after('unit_large');
                }
                if (!Schema::hasColumn('master_products', 'price')) {
                    $table->decimal('price', 12, 2)->nullable()->after('description');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('master_products')) {
            Schema::table('master_products', function (Blueprint $table) {
                if (Schema::hasColumn('master_products', 'unit_small')) $table->dropColumn('unit_small');
                if (Schema::hasColumn('master_products', 'unit_large')) $table->dropColumn('unit_large');
                if (Schema::hasColumn('master_products', 'conversion_qty')) $table->dropColumn('conversion_qty');
                if (Schema::hasColumn('master_products', 'price')) $table->dropColumn('price');
            });
        }
    }
};
