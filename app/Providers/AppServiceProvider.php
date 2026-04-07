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
        ];

        $settings = $defaults;
        if (Schema::hasTable('app_settings')) {
            $stored = AppSetting::whereIn('key', array_keys($defaults))->pluck('value', 'key')->toArray();
            $settings = array_merge($defaults, array_filter($stored, static fn ($value) => $value !== null && $value !== ''));
        }

        View::share('publicSettings', $settings);
    }
}
