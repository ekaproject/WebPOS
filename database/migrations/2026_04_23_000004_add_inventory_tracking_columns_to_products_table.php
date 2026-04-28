<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'distributor_id')) {
                $table->foreignId('distributor_id')->nullable()->after('category_id')->constrained()->nullOnDelete();
            }

            if (!Schema::hasColumn('products', 'source_type')) {
                $table->enum('source_type', ['manual', 'qc', 'return_replacement'])->default('manual')->after('is_active');
            }

            if (!Schema::hasColumn('products', 'source_reference_id')) {
                $table->unsignedBigInteger('source_reference_id')->nullable()->after('source_type');
            }

            if (!Schema::hasColumn('products', 'inbound_item_id')) {
                $table->foreignId('inbound_item_id')->nullable()->after('source_reference_id')->constrained('inbound_items')->nullOnDelete();
            }

            if (!Schema::hasColumn('products', 'return_id')) {
                $table->foreignId('return_id')->nullable()->after('inbound_item_id')->constrained('returns')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'return_id')) {
                $table->dropConstrainedForeignId('return_id');
            }

            if (Schema::hasColumn('products', 'inbound_item_id')) {
                $table->dropConstrainedForeignId('inbound_item_id');
            }

            if (Schema::hasColumn('products', 'distributor_id')) {
                $table->dropConstrainedForeignId('distributor_id');
            }

            if (Schema::hasColumn('products', 'source_reference_id')) {
                $table->dropColumn('source_reference_id');
            }

            if (Schema::hasColumn('products', 'source_type')) {
                $table->dropColumn('source_type');
            }
        });
    }
};
