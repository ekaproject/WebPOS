<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained()->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->date('return_date');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
