<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('distributors')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE distributors ADD UNIQUE distributors_name_unique (name)');
        } catch (\Throwable $e) {
            // Ignore when unique index already exists or duplicate data blocks migration.
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('distributors')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE distributors DROP INDEX distributors_name_unique');
        } catch (\Throwable $e) {
            // Ignore when index does not exist.
        }
    }
};
