<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('contact_person')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('distributor_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['distributor_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributor_product');
        Schema::dropIfExists('distributors');
    }
};
