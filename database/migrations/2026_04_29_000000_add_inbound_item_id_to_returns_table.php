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
            if (!Schema::hasColumn('returns', 'inbound_item_id')) {
                $table->foreignId('inbound_item_id')->nullable()->after('id')->constrained('inbound_items')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('returns')) {
            return;
        }

        Schema::table('returns', function (Blueprint $table) {
            if (Schema::hasColumn('returns', 'inbound_item_id')) {
                $table->dropConstrainedForeignId('inbound_item_id');
            }
        });
    }
};
