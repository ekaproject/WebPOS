<?php

namespace App\Providers;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $defaults = [
            'store_name' => 'Nexus Retail',
            'store_email' => 'help@nexusretail.com',
            'store_phone' => '(021) 555-0123',
            'store_address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'tax_percent' => '11',
            'footer_text' => 'Hak Cipta Dilindungi.',
            'landing_hero_title' => 'Satu Tempat untuk Semua Kebutuhan.',
            'landing_hero_description' => 'Mulai dari bahan makanan segar, perlengkapan rumah tangga, hingga bayar tagihan. Belanja cerdas, hidup lebih berkualitas.',
            'landing_solusi_text' => 'Solusi Belanja Terlengkap',
            'landing_solusi_desc' => 'Pilihan produk lengkap untuk kebutuhan harian Anda.',
            'landing_feature_1_title' => 'Belanja Cepat',
            'landing_feature_1_desc' => 'Temukan produk kebutuhan Anda dengan mudah dan cepat.',
            'landing_feature_2_title' => 'Promo Menarik',
            'landing_feature_2_desc' => 'Nikmati harga terbaik dari produk pilihan kami.',
            'landing_about_desc' => 'Solusi belanja retail terlengkap dan modern. Kualitas terbaik dari berbagai kategori kebutuhan hidup Anda dalam satu atap digital.',
            'landing_location_photo_1' => null,
            'landing_location_photo_2' => null,
            'landing_location_photo_3' => null,
        ];

        $settings = $defaults;
        if (Schema::hasTable('app_settings')) {
            $stored = AppSetting::whereIn('key', array_keys($defaults))->pluck('value', 'key')->toArray();
            $settings = array_merge($defaults, array_filter($stored, static fn ($value) => $value !== null && $value !== ''));
        }

        View::share('publicSettings', $settings);
    }
}
