<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('qc_items')) {
            return;
        }

        Schema::create('qc_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inbound_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('good_qty');
            $table->unsignedInteger('damaged_qty');
            $table->timestamp('checked_at');
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique('inbound_item_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_items');
    }
};
