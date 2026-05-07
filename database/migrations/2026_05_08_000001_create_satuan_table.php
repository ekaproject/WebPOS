<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);          // Contoh: Pieces, Kilogram, Liter
            $table->string('singkatan', 20)->unique(); // Contoh: pcs, kg, L, botol, karton
            $table->timestamps();
        });

        // Isi data awal satuan umum
        \DB::table('satuan')->insert([
            ['nama' => 'Pieces',  'singkatan' => 'pcs',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Kilogram','singkatan' => 'kg',     'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Gram',    'singkatan' => 'gr',     'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Liter',   'singkatan' => 'L',      'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Mililiter','singkatan' => 'ml',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Botol',   'singkatan' => 'btl',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Karton',  'singkatan' => 'krt',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dus',     'singkatan' => 'dus',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sachet',  'singkatan' => 'scht',   'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Lusin',   'singkatan' => 'lsn',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('satuan');
    }
};
