<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileProductController;
use App\Http\Controllers\Api\MobileTransactionController;
use App\Http\Controllers\Api\MobileSettingsController;

Route::get('/distributor/{id}/products', [ReturnController::class, 'getProductsByDistributor']);

// API untuk Mobile POS
Route::prefix('mobile')->group(function () {
    // Auth — tidak butuh token
    Route::post('/auth/login',  [MobileAuthController::class, 'login']);
    Route::post('/auth/logout', [MobileAuthController::class, 'logout']);
    Route::get('/auth/me',      [MobileAuthController::class, 'me']);

    // Data produk, kategori, settings — public (kasir sudah login di app)
    Route::get('/products', [MobileProductController::class, 'index']);
    Route::get('/products/{id}', [MobileProductController::class, 'show']);
    Route::get('/categories', [MobileProductController::class, 'categories']);
    Route::get('/settings', [MobileSettingsController::class, 'index']);

    Route::get('/transactions', [MobileTransactionController::class, 'index']);
    Route::post('/transactions', [MobileTransactionController::class, 'store']);
});
