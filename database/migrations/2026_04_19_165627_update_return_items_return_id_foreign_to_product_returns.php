<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('return_items') || !Schema::hasTable('product_returns')) {
            return;
        }

        try {
            Schema::table('return_items', function (Blueprint $table) {
                $table->dropForeign(['return_id']);
            });
        } catch (\Throwable $e) {
            // Ignore when the old foreign key does not exist yet.
        }

        try {
            Schema::table('return_items', function (Blueprint $table) {
                $table->foreign('return_id')->references('id')->on('product_returns')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            // Ignore when the target foreign key already exists.
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('return_items') || !Schema::hasTable('returns')) {
            return;
        }

        try {
            Schema::table('return_items', function (Blueprint $table) {
                $table->dropForeign(['return_id']);
            });
        } catch (\Throwable $e) {
            // Ignore when the product_returns foreign key does not exist.
        }

        try {
            Schema::table('return_items', function (Blueprint $table) {
                $table->foreign('return_id')->references('id')->on('returns')->onDelete('cascade');
            });
        } catch (\Throwable $e) {
            // Ignore when the returns foreign key already exists.
        }
    }
};
