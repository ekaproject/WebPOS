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
        if (Schema::hasTable('inbound_items') && !Schema::hasColumn('inbound_items', 'note')) {
            Schema::table('inbound_items', function (Blueprint $table) {
                $table->text('note')->nullable()->after('expired_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('inbound_items') && Schema::hasColumn('inbound_items', 'note')) {
            Schema::table('inbound_items', function (Blueprint $table) {
                $table->dropColumn('note');
            });
        }
    }
};
