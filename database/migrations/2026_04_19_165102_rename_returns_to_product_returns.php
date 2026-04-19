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
        Schema::rename('returns', 'product_returns');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('product_returns', 'returns');
    }
};
