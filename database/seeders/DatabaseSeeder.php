<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DigitalService;
use App\Models\Product;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@nexusretail.com'],
            [
                'name' => 'Admin Nexus',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Categories
        $categories = [
            ['name' => 'Produk Segar',          'slug' => 'produk-segar',    'icon' => 'nutrition',         'description' => 'Buah, Sayur, Daging',          'type' => 'fresh'],
            ['name' => 'Makanan & Minuman',      'slug' => 'makanan-minuman', 'icon' => 'restaurant',        'description' => 'Snack, Minuman, Instan',        'type' => 'fnb'],
            ['name' => 'Kebutuhan Rumah Tangga', 'slug' => 'rumah-tangga',    'icon' => 'clean_hands',       'description' => 'Pembersih, Perawatan Diri',     'type' => 'fmcg'],
            ['name' => 'Kesehatan & Farmasi',    'slug' => 'kesehatan',       'icon' => 'medical_services',  'description' => 'Vitamin, Obat-obatan',          'type' => 'fmcg'],
            ['name' => 'Layanan Digital',        'slug' => 'digital',         'icon' => 'devices',           'description' => 'Top-up & Tagihan',              'type' => 'digital'],
            ['name' => 'Barang Umum',            'slug' => 'barang-umum',     'icon' => 'category',          'description' => 'Alat Tulis, Rumah Tangga',      'type' => 'fmcg'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat + ['is_active' => true]);
        }

        // Sample products
        $fresh = Category::where('slug', 'produk-segar')->first();
        $fnb   = Category::where('slug', 'makanan-minuman')->first();
        $hh    = Category::where('slug', 'rumah-tangga')->first();
        $health = Category::where('slug', 'kesehatan')->first();

        $products = [
            ['name' => 'Salmon Fillet Premium 200g',  'sku' => 'PRD-001', 'category_id' => $fresh->id,  'price' => 85000,  'stock' => 50,  'min_stock' => 10, 'unit' => 'pcs',    'expires_at' => now()->addDays(5)],
            ['name' => 'Paket Camilan Keluarga',       'sku' => 'PRD-002', 'category_id' => $fnb->id,    'price' => 99000,  'stock' => 120, 'min_stock' => 20, 'unit' => 'paket',  'expires_at' => now()->addMonths(6)],
            ['name' => 'MultiVitamin Nexus Gold 30s',  'sku' => 'PRD-003', 'category_id' => $health->id, 'price' => 155000, 'stock' => 8,   'min_stock' => 10, 'unit' => 'botol',  'expires_at' => now()->addYear()],
            ['name' => 'Eco-Bathroom Essentials Kit',  'sku' => 'PRD-004', 'category_id' => $hh->id,     'price' => 210000, 'stock' => 30,  'min_stock' => 5,  'unit' => 'set',    'expires_at' => null],
            ['name' => 'Beras Premium 5kg',            'sku' => 'PRD-005', 'category_id' => $fnb->id,    'price' => 75000,  'stock' => 200, 'min_stock' => 30, 'unit' => 'karung', 'expires_at' => now()->addMonths(12)],
            ['name' => 'Susu UHT Full Cream 1L',       'sku' => 'PRD-006', 'category_id' => $fnb->id,    'price' => 18500,  'stock' => 3,   'min_stock' => 15, 'unit' => 'kotak',  'expires_at' => now()->addDays(2)],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['sku' => $p['sku']], $p + ['is_active' => true]);
        }

        // Digital Services
        $digitalServices = [
            ['name' => 'Pulsa & Data', 'slug' => 'pulsa-data', 'icon' => 'vibration', 'provider' => 'Semua Operator', 'base_price' => 5000, 'sort_order' => 1],
            ['name' => 'Token PLN', 'slug' => 'token-pln', 'icon' => 'bolt', 'provider' => 'PLN', 'base_price' => 20000, 'sort_order' => 2],
            ['name' => 'Game Voucher', 'slug' => 'game-voucher', 'icon' => 'sports_esports', 'provider' => 'Mobile & PC', 'base_price' => 10000, 'sort_order' => 3],
            ['name' => 'PDAM', 'slug' => 'pdam', 'icon' => 'water_drop', 'provider' => 'PDAM', 'base_price' => 0, 'sort_order' => 4],
            ['name' => 'Internet & TV', 'slug' => 'internet-tv', 'icon' => 'router', 'provider' => 'IndiHome, First Media', 'base_price' => 0, 'sort_order' => 5],
            ['name' => 'BPJS', 'slug' => 'bpjs', 'icon' => 'local_hospital', 'provider' => 'BPJS Kesehatan & TK', 'base_price' => 0, 'sort_order' => 6],
            ['name' => 'Pajak & Retribusi', 'slug' => 'pajak-retribusi', 'icon' => 'receipt_long', 'provider' => 'Samsat', 'base_price' => 0, 'sort_order' => 7],
            ['name' => 'Transfer Saldo', 'slug' => 'transfer-saldo', 'icon' => 'savings', 'provider' => 'e-Wallet & Bank', 'base_price' => 0, 'sort_order' => 8],
        ];

        foreach ($digitalServices as $ds) {
            DigitalService::firstOrCreate(['slug' => $ds['slug']], $ds + ['is_active' => true]);
        }

        // Promos
        $cat = Category::first();
        $promos = [
            ['title' => 'Diskon 20% Produk Segar', 'type' => 'percent', 'discount_value' => 20, 'min_purchase' => 100000, 'category_id' => Category::where('slug','produk-segar')->value('id'), 'start_date' => now(), 'end_date' => now()->addDays(7), 'is_active' => true],
            ['title' => 'Hemat Rp50.000 Belanja > 500rb', 'type' => 'fixed', 'discount_value' => 50000, 'min_purchase' => 500000, 'start_date' => now(), 'end_date' => now()->addDays(14), 'is_active' => true],
            ['title' => 'Beli 2 Gratis 1 Minuman', 'type' => 'free_item', 'discount_value' => 1, 'min_purchase' => null, 'category_id' => Category::where('slug','makanan-minuman')->value('id'), 'start_date' => now(), 'end_date' => now()->addDays(3), 'is_active' => true],
            ['title' => 'Flash Sale Akhir Bulan', 'type' => 'percent', 'discount_value' => 30, 'min_purchase' => 200000, 'start_date' => now()->addDays(20), 'end_date' => now()->addDays(30), 'is_active' => true],
        ];

        foreach ($promos as $p) {
            Promo::firstOrCreate(['title' => $p['title']], $p);
        }
    }
}
