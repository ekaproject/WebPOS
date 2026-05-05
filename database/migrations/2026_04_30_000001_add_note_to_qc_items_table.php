<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('qc_items')) {
            return;
        }

        Schema::table('qc_items', function (Blueprint $table) {
            if (!Schema::hasColumn('qc_items', 'note')) {
                $table->text('note')->nullable()->after('checked_by');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('qc_items')) {
            return;
        }

        Schema::table('qc_items', function (Blueprint $table) {
            if (Schema::hasColumn('qc_items', 'note')) {
                $table->dropColumn('note');
            }
        });
    }
};
