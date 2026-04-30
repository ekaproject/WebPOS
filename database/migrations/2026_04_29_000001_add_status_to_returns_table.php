<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('returns')) {
            return;
        }

        Schema::table('returns', function (Blueprint $table) {
            if (!Schema::hasColumn('returns', 'status')) {
                $table->enum('status', ['pending', 'confirmed', 'completed'])->default('pending')->after('return_date');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('returns')) {
            return;
        }

        Schema::table('returns', function (Blueprint $table) {
            if (Schema::hasColumn('returns', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
