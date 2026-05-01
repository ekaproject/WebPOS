<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Api\MobileProductController;
use App\Http\Controllers\Api\MobileTransactionController;

Route::get('/distributor/{id}/products', [ReturnController::class, 'getProductsByDistributor']);

// API untuk Mobile POS
Route::prefix('mobile')->group(function () {
    Route::get('/products', [MobileProductController::class, 'index']);
    Route::get('/products/{id}', [MobileProductController::class, 'show']);
    Route::get('/categories', [MobileProductController::class, 'categories']);

    Route::get('/transactions', [MobileTransactionController::class, 'index']);
    Route::post('/transactions', [MobileTransactionController::class, 'store']);
});
