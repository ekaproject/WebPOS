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
use App\Http\Controllers\Admin\DistributorController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\InboundItemController;
use App\Http\Controllers\Api\DashboardStockAlertController;

// Public landing page
Route::get('/', function () {
    $categories = \App\Models\Category::query()
        ->visibleForMenu()
        ->where('is_active', true)
        ->orderBy('name')
        ->get();

    $landingProducts = \App\Models\Product::query()
        ->with('category')
        ->whereIn('id', \App\Models\Product::query()
            ->selectRaw('MAX(id)')
            ->where('is_active', true)
            ->whereHas('category', function ($query) {
                $query->visibleForMenu()->where('is_active', true);
            })
            ->groupBy('name')
        )
        ->orderByDesc('stock')
        ->orderByDesc('updated_at')
        ->take(24)
        ->get();

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

    return view('landing', compact('categories', 'landingProducts', 'promos', 'featuredProducts'));
})->name('home');
Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories', function () {
    return redirect()->route('categories.index', request()->query());
})->name('categories.index.legacy');
Route::get('/kategori/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/categories/{category:slug}', function (\App\Models\Category $category) {
    return redirect()->route('categories.show', array_merge([
        'category' => $category->slug,
    ], request()->query()));
})->name('categories.show.legacy');
Route::get('/promos', [PromoController::class, 'index'])->name('promos.index');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->get('/api/dashboard/stock-alert', DashboardStockAlertController::class)
    ->name('api.dashboard.stock-alert');

// Admin routes (auth protected)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('categories/{category}/next-sku', [ProductController::class, 'nextSku'])->name('categories.next-sku');
    Route::resource('products', ProductController::class);
    Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
    Route::resource('promos', AdminPromoController::class)->except(['show']);
    Route::get('distributors', [DistributorController::class, 'index'])->name('distributors.index');
    Route::get('distributors/create', [DistributorController::class, 'create'])->name('distributors.create');
    Route::post('distributors', [DistributorController::class, 'store'])->name('distributors.store');
    Route::get('distributors/{distributor}', [DistributorController::class, 'show'])->name('distributors.show');
    Route::get('distributors/{distributor}/edit', [DistributorController::class, 'edit'])->name('distributors.edit');
    Route::put('distributors/{distributor}', [DistributorController::class, 'update'])->name('distributors.update');
    Route::delete('distributors/{distributor}', [DistributorController::class, 'destroy'])->name('distributors.destroy');
    Route::get('inbound-items', [InboundItemController::class, 'index'])->name('inbound-items.index');
    Route::get('inbound-items/create', [InboundItemController::class, 'create'])->name('inbound-items.create');
    Route::post('inbound-items', [InboundItemController::class, 'store'])->name('inbound-items.store');
    Route::get('inbound-items/{inboundItem}', [InboundItemController::class, 'show'])->name('inbound-items.show');
    Route::post('inbound-items/{inboundItem}/qc', [InboundItemController::class, 'processQc'])->name('inbound-items.qc.process');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('reports/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::resource('team', TeamController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('returns/{inventoryReturn}', [ReturnController::class, 'show'])->name('returns.show');
    Route::post('returns/{inventoryReturn}/complete', [ReturnController::class, 'complete'])->name('returns.complete');
});