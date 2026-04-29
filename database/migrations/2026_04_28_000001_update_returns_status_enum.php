<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('returns') || !Schema::hasColumn('returns', 'status')) {
            return;
        }

        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE returns MODIFY COLUMN status ENUM('pending', 'confirmed', 'completed') DEFAULT 'pending'");
            return;
        }

        if ($driver === 'sqlite') {
            $this->rebuildReturnsTable(['pending', 'confirmed', 'completed']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('returns') || !Schema::hasColumn('returns', 'status')) {
            return;
        }

        $driver = DB::getDriverName();

        DB::table('returns')
            ->where('status', 'confirmed')
            ->update(['status' => 'pending']);

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE returns MODIFY COLUMN status ENUM('pending', 'completed') DEFAULT 'pending'");
            return;
        }

        if ($driver === 'sqlite') {
            $this->rebuildReturnsTable(['pending', 'completed']);
        }
    }

    /**
     * Rebuild the returns table for SQLite enum changes.
     *
     * @param  array<int, string>  $statuses
     */
    private function rebuildReturnsTable(array $statuses): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::rename('returns', 'returns_backup');

        Schema::create('returns', function (Blueprint $table) use ($statuses) {
            $table->id();
            $table->foreignId('inbound_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('distributor_id')->constrained()->cascadeOnDelete();
            $table->string('product_name');
            $table->unsignedInteger('qty');
            $table->enum('status', $statuses)->default('pending');
            $table->timestamp('resolved_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        DB::table('returns')->insertUsing(
            ['id', 'inbound_item_id', 'distributor_id', 'product_name', 'qty', 'status', 'resolved_at', 'note', 'created_at', 'updated_at'],
            DB::table('returns_backup')->select([
                'id',
                'inbound_item_id',
                'distributor_id',
                'product_name',
                'qty',
                'status',
                'resolved_at',
                'note',
                'created_at',
                'updated_at',
            ])
        );

        Schema::drop('returns_backup');
        Schema::enableForeignKeyConstraints();
    }
};
