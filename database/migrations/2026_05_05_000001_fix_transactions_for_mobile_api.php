<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat user_id nullable agar mobile bisa buat transaksi tanpa login
        DB::statement("ALTER TABLE transactions MODIFY user_id BIGINT UNSIGNED NULL");

        // 2. Tambah 'completed' ke status enum
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('pending', 'paid', 'cancelled', 'completed') DEFAULT 'pending'");

        // 3. Tambah 'qris' ke payment_method enum
        DB::statement("ALTER TABLE transactions MODIFY payment_method ENUM('cash', 'transfer', 'ewallet', 'qris') DEFAULT 'cash'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY user_id BIGINT UNSIGNED NOT NULL");
        DB::statement("ALTER TABLE transactions MODIFY status ENUM('pending', 'paid', 'cancelled') DEFAULT 'pending'");
        DB::statement("ALTER TABLE transactions MODIFY payment_method ENUM('cash', 'transfer', 'ewallet') DEFAULT 'cash'");
    }
};
