<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        /*
        |--------------------------------------------------------------------------
        | MYSQL / MARIADB
        |--------------------------------------------------------------------------
        */
        if (in_array($driver, ['mysql', 'mariadb'], true)) {

            // user_id nullable
            DB::statement("
                ALTER TABLE transactions
                MODIFY user_id BIGINT UNSIGNED NULL
            ");

            // status enum tambah completed
            DB::statement("
                ALTER TABLE transactions
                MODIFY status ENUM('pending','paid','cancelled','completed')
                DEFAULT 'pending'
            ");

            // payment_method tambah qris
            DB::statement("
                ALTER TABLE transactions
                MODIFY payment_method ENUM('cash','transfer','ewallet','qris')
                DEFAULT 'cash'
            ");

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SQLITE
        |--------------------------------------------------------------------------
        */
        if ($driver === 'sqlite') {

            Schema::disableForeignKeyConstraints();

            Schema::rename('transactions', 'transactions_backup');

            Schema::create('transactions', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

                $table->string('invoice')->nullable();
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('discount', 15, 2)->default(0);
                $table->decimal('tax', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('change_amount', 15, 2)->default(0);

                $table->enum('status', [
                    'pending',
                    'paid',
                    'cancelled',
                    'completed'
                ])->default('pending');

                $table->enum('payment_method', [
                    'cash',
                    'transfer',
                    'ewallet',
                    'qris'
                ])->default('cash');

                $table->timestamps();
            });

            DB::table('transactions')->insertUsing(
                [
                    'id',
                    'user_id',
                    'invoice',
                    'subtotal',
                    'discount',
                    'tax',
                    'total',
                    'paid_amount',
                    'change_amount',
                    'status',
                    'payment_method',
                    'created_at',
                    'updated_at'
                ],
                DB::table('transactions_backup')->select([
                    'id',
                    'user_id',
                    'invoice',
                    'subtotal',
                    'discount',
                    'tax',
                    'total',
                    'paid_amount',
                    'change_amount',
                    'status',
                    'payment_method',
                    'created_at',
                    'updated_at'
                ])
            );

            Schema::drop('transactions_backup');

            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        /*
        |--------------------------------------------------------------------------
        | MYSQL / MARIADB
        |--------------------------------------------------------------------------
        */
        if (in_array($driver, ['mysql', 'mariadb'], true)) {

            DB::statement("
                ALTER TABLE transactions
                MODIFY user_id BIGINT UNSIGNED NOT NULL
            ");

            DB::statement("
                ALTER TABLE transactions
                MODIFY status ENUM('pending','paid','cancelled')
                DEFAULT 'pending'
            ");

            DB::statement("
                ALTER TABLE transactions
                MODIFY payment_method ENUM('cash','transfer','ewallet')
                DEFAULT 'cash'
            ");

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | SQLITE
        |--------------------------------------------------------------------------
        */
        if ($driver === 'sqlite') {

            Schema::disableForeignKeyConstraints();

            Schema::rename('transactions', 'transactions_backup');

            Schema::create('transactions', function (Blueprint $table) {
                $table->id();

                $table->foreignId('user_id')->constrained()->cascadeOnDelete();

                $table->string('invoice')->nullable();
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('discount', 15, 2)->default(0);
                $table->decimal('tax', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('change_amount', 15, 2)->default(0);

                $table->enum('status', [
                    'pending',
                    'paid',
                    'cancelled'
                ])->default('pending');

                $table->enum('payment_method', [
                    'cash',
                    'transfer',
                    'ewallet'
                ])->default('cash');

                $table->timestamps();
            });

            DB::table('transactions')->insertUsing(
                [
                    'id',
                    'user_id',
                    'invoice',
                    'subtotal',
                    'discount',
                    'tax',
                    'total',
                    'paid_amount',
                    'change_amount',
                    'status',
                    'payment_method',
                    'created_at',
                    'updated_at'
                ],
                DB::table('transactions_backup')->select([
                    'id',
                    'user_id',
                    'invoice',
                    'subtotal',
                    'discount',
                    'tax',
                    'total',
                    'paid_amount',
                    'change_amount',
                    'status',
                    'payment_method',
                    'created_at',
                    'updated_at'
                ])
            );

            Schema::drop('transactions_backup');

            Schema::enableForeignKeyConstraints();
        }
    }
};