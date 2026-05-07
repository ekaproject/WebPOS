<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_products', function (Blueprint $table) {
            // Tambah field ukuran produk (cth: 250ml, 1kg, 500gr)
            if (!Schema::hasColumn('master_products', 'ukuran')) {
                $table->string('ukuran', 100)->nullable()->after('name');
            }

            // Tambah FK ke tabel satuan (menggantikan field unit string)
            if (!Schema::hasColumn('master_products', 'satuan_id')) {
                $table->foreignId('satuan_id')
                    ->nullable()
                    ->after('ukuran')
                    ->constrained('satuan')
                    ->nullOnDelete();
            }

            // Unique constraint: nama + ukuran + satuan_id agar tidak double
            // Kombinasi ini yang menjamin produk tidak duplikat
            $table->unique(['name', 'ukuran', 'satuan_id'], 'master_products_name_ukuran_satuan_unique');
        });
    }

    public function down(): void
    {
        Schema::table('master_products', function (Blueprint $table) {
            $table->dropUnique('master_products_name_ukuran_satuan_unique');

            if (Schema::hasColumn('master_products', 'satuan_id')) {
                $table->dropConstrainedForeignId('satuan_id');
            }

            if (Schema::hasColumn('master_products', 'ukuran')) {
                $table->dropColumn('ukuran');
            }
        });
    }
};
