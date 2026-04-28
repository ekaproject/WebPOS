<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('returns')) {
            return;
        }

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbound_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distributor_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->unsignedInteger('qty');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->timestamp('resolved_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
