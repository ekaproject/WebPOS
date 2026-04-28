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
            if (!Schema::hasColumn('inbound_items', 'category_id')) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->after('product_name')
                    ->constrained()
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('inbound_items', 'product_photo')) {
                $table->string('product_photo')->nullable()->after('category_id');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::table('inbound_items', function (Blueprint $table) {
            if (Schema::hasColumn('inbound_items', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }

            if (Schema::hasColumn('inbound_items', 'product_photo')) {
                $table->dropColumn('product_photo');
            }
        });
    }
};
