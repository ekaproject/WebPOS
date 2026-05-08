<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'unit_small')) {
                    $table->string('unit_small')->default('pcs')->after('unit');
                }
                if (!Schema::hasColumn('products', 'unit_large')) {
                    $table->string('unit_large')->nullable()->after('unit_small');
                }
                if (!Schema::hasColumn('products', 'conversion_qty')) {
                    $table->integer('conversion_qty')->default(1)->after('unit_large');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'unit_small')) $table->dropColumn('unit_small');
                if (Schema::hasColumn('products', 'unit_large')) $table->dropColumn('unit_large');
                if (Schema::hasColumn('products', 'conversion_qty')) $table->dropColumn('conversion_qty');
            });
        }
    }
};
