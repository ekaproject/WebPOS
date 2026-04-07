<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->string('voucher_code')->nullable()->unique()->after('min_purchase');
            $table->unsignedInteger('voucher_quota')->nullable()->after('voucher_code');
            $table->unsignedInteger('voucher_claimed')->default(0)->after('voucher_quota');
        });
    }

    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropUnique(['voucher_code']);
            $table->dropColumn(['voucher_code', 'voucher_quota', 'voucher_claimed']);
        });
    }
};
