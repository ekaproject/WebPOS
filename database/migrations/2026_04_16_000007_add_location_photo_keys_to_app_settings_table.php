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
                'key' => 'landing_location_photo_1',
                'value' => null,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'landing_location_photo_2',
                'value' => null,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'key' => 'landing_location_photo_3',
                'value' => null,
                'updated_by' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('app_settings')
            ->whereIn('key', [
                'landing_location_photo_1',
                'landing_location_photo_2',
                'landing_location_photo_3',
            ])
            ->delete();
    }
};
