<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue      = Transaction::where('status', 'paid')->sum('total_amount');
        $digitalSales      = Transaction::where('status', 'paid')
            ->whereHas('items.product.category', fn ($q) => $q->where('type', 'digital'))
            ->sum('total_amount');
        $lowStockProducts  = Product::whereColumn('stock', '<=', 'min_stock')->count();
        $expiringProducts  = Product::whereNotNull('expires_at')
            ->whereDate('expires_at', '<=', now()->addDays(3))
            ->count();
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();
        $topProducts = Product::withCount('transactionItems')
            ->orderByDesc('transaction_items_count')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'digitalSales', 'lowStockProducts',
            'expiringProducts', 'recentTransactions', 'topProducts'
        ));
    }
}
