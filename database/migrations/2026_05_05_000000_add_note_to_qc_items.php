<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('qc_items') && ! Schema::hasColumn('qc_items', 'note')) {
            Schema::table('qc_items', function (Blueprint $table) {
                $table->text('note')->nullable()->after('damaged_qty');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('qc_items') && Schema::hasColumn('qc_items', 'note')) {
            Schema::table('qc_items', function (Blueprint $table) {
                $table->dropColumn('note');
            });
        }
    }
};
