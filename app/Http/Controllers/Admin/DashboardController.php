<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue      = Transaction::where('status', 'paid')->sum('total_amount');
        $digitalSales      = Transaction::where('status', 'paid')
            ->whereHas('items.product.category', fn ($q) => $q->where('type', 'digital'))
            ->sum('total_amount');
        $lowStockProducts  = Product::whereColumn('stock', '<', 'min_stock')->count();
        $lowStockProductList = Product::query()
            ->select(['id', 'name', 'stock', 'min_stock'])
            ->whereColumn('stock', '<', 'min_stock')
            ->orderBy('stock')
            ->orderBy('name')
            ->take(5)
            ->get();
        $expiringProducts  = Product::whereNotNull('expires_at')
            ->whereDate('expires_at', '<=', now()->addDays(3))
            ->count();
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();
        $topProducts = Product::query()
            ->withSum([
                'transactionItems as total_sold' => function ($query) {
                    $query->whereHas('transaction', function ($transaction) {
                        $transaction->where('status', 'paid');
                    });
                },
            ], 'quantity')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'digitalSales', 'lowStockProducts',
            'lowStockProductList', 'expiringProducts', 'recentTransactions', 'topProducts'
        ));
    }

    /**
     * Return JSON data for the transaction line chart.
     * Supports ?filter=today|week|month (default: month)
     */
    public function chartData(Request $request)
    {
        $filter = $request->input('filter', 'month');

        switch ($filter) {
            case 'today':
                $start = Carbon::today();
                $end   = Carbon::today()->endOfDay();
                break;
            case 'week':
                $start = Carbon::now()->startOfWeek(Carbon::MONDAY);
                $end   = Carbon::now()->endOfWeek(Carbon::SUNDAY);
                break;
            default: // month
                $start = Carbon::now()->startOfMonth();
                $end   = Carbon::now()->endOfMonth();
                break;
        }

        // Aggregate paid transactions grouped by date
        $transactions = Transaction::where('status', 'paid')
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Build full date range so days with zero transactions still appear
        $labels = [];
        $totals = [];
        $counts = [];

        if ($filter === 'today') {
            // For "today", show hourly breakdown instead of daily
            for ($h = 0; $h < 24; $h++) {
                $hourStart = Carbon::today()->addHours($h);
                $hourEnd   = (clone $hourStart)->addHour();
                $label     = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';

                $hourData = Transaction::where('status', 'paid')
                    ->whereBetween('created_at', [$hourStart, $hourEnd])
                    ->selectRaw('SUM(total_amount) as total, COUNT(*) as count')
                    ->first();

                $labels[] = $label;
                $totals[] = (int) ($hourData->total ?? 0);
                $counts[] = (int) ($hourData->count ?? 0);
            }
        } else {
            $period = CarbonPeriod::create($start, $end);
            foreach ($period as $date) {
                $key = $date->format('Y-m-d');
                $labels[] = $date->translatedFormat('d M');
                $totals[] = (int) ($transactions[$key]->total ?? 0);
                $counts[] = (int) ($transactions[$key]->count ?? 0);
            }
        }

        return response()->json([
            'labels' => $labels,
            'totals' => $totals,
            'counts' => $counts,
            'filter' => $filter,
        ]);
    }
}
