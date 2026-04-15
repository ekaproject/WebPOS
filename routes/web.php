<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\SettingController;

// Public landing page
Route::get('/', function () {
    $categories = \App\Models\Category::where('is_active', true)->orderBy('name')->get();
    $promos = \App\Models\Promo::with('category')
        ->where('is_active', true)
        ->whereDate('end_date', '>=', now()->toDateString())
        ->orderBy('start_date')
        ->take(4)
        ->get();
    $featuredProducts = \App\Models\Promo::with(['product.category'])
        ->whereNotNull('product_id')
        ->where('is_active', true)
        ->whereDate('end_date', '>=', now()->toDateString())
        ->orderBy('start_date')
        ->take(4)
        ->get();

    return view('landing', compact('categories', 'promos', 'featuredProducts'));
})->name('home');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/promos', [PromoController::class, 'index'])->name('promos.index');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Admin routes (auth protected)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('categories/{category}/next-sku', [ProductController::class, 'nextSku'])->name('categories.next-sku');
    Route::resource('products', ProductController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('promos', AdminPromoController::class)->except(['show']);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::resource('team', TeamController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
});