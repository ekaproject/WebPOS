<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('app_settings')->insertOrIgnore([
            [
                'key'        => 'store_name',
                'value'      => 'POS TOSERBA',
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'store_address',
                'value'      => 'jl. indah no.15, Sidoarjo',
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key'        => 'cashier_name',
                'value'      => 'Dewi',
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('app_settings')
            ->whereIn('key', ['store_name', 'store_address', 'cashier_name'])
            ->delete();
    }
};
