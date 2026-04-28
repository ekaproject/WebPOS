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
            if (!Schema::hasColumn('inbound_items', 'purchase_price')) {
                $table->decimal('purchase_price', 15, 2)->default(0)->after('product_photo');
            }

            if (!Schema::hasColumn('inbound_items', 'selling_price')) {
                $table->decimal('selling_price', 15, 2)->default(0)->after('purchase_price');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::table('inbound_items', function (Blueprint $table) {
            if (Schema::hasColumn('inbound_items', 'selling_price')) {
                $table->dropColumn('selling_price');
            }

            if (Schema::hasColumn('inbound_items', 'purchase_price')) {
                $table->dropColumn('purchase_price');
            }
        });
    }
};
