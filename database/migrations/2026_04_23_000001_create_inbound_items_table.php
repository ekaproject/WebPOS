<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::create('inbound_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->unsignedInteger('quantity_inbound');
            $table->date('inbound_date');
            $table->date('expired_date');
            $table->enum('qc_status', ['pending', 'completed'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['distributor_id', 'inbound_date']);
            $table->index('qc_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbound_items');
    }
};
