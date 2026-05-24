<?php

namespace App\Providers;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

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
        // Force HTTPS on production (app is behind proxy that terminates SSL)
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        $defaults = [
            'store_name' => 'ILS MART',
            'store_email' => 'help@nexusretail.com',
            'store_phone' => '(021) 555-0123',
            'store_whatsapp' => '6281234567890',
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

        if (! $this->app->runningInConsole()) {
            try {
                if (Schema::hasTable('app_settings')) {
                    $stored = AppSetting::whereIn('key', array_keys($defaults))->pluck('value', 'key')->toArray();
                    $settings = array_merge($defaults, array_filter($stored, static fn ($value) => $value !== null && $value !== ''));
                }
            } catch (Throwable $e) {
                // Keep defaults when the database is unavailable during bootstrap.
            }
        }

        View::share('publicSettings', $settings);

        // Helper: storage_img('path/to/img.jpg') → URL ke file, atau fallback ke no-image.svg
        Blade::directive('storageImg', function ($expression) {
            return "<?php echo (function(\$p){ return (\$p && file_exists(public_path('storage/'.\$p))) ? asset('storage/'.\$p) : asset('images/no-image.svg'); })($expression); ?>";
        });
    }
}
