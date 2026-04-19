<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ReturnController;

Route::get('/distributor/{id}/products', [ReturnController::class, 'getProductsByDistributor']);
