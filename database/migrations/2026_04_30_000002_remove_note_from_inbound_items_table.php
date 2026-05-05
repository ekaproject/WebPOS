<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::table('inbound_items', function (Blueprint $table) {
            if (Schema::hasColumn('inbound_items', 'note')) {
                $table->dropColumn('note');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('inbound_items')) {
            return;
        }

        Schema::table('inbound_items', function (Blueprint $table) {
            if (!Schema::hasColumn('inbound_items', 'note')) {
                $table->text('note')->nullable()->after('qc_status');
            }
        });
    }
};
