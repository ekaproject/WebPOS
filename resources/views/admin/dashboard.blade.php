@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- Welcome Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-on-surface-variant mt-1">Ringkasan performa toko hari ini, {{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="flex items-center gap-3 bg-surface-container-low border border-outline-variant/30 rounded-xl px-4 py-2.5 self-start">
            <span class="material-symbols-outlined text-on-surface-variant text-xl">event</span>
            <span class="text-sm font-semibold text-on-surface">{{ now()->format('d M Y') }}</span>
        </div>
    </div>

    {{-- Urgent Alert --}}
    @if($lowStockProducts > 0 || $expiringProducts > 0)
    <div class="bg-error-container border border-error/20 rounded-2xl p-4 flex items-center gap-4">
        <span class="material-symbols-outlined text-error text-2xl" style="font-variation-settings: 'FILL' 1;">warning</span>
        <div class="text-sm">
            <span class="font-bold text-on-error-container">Perhatian Segera: </span>
            @if($lowStockProducts > 0)
                <span class="text-on-error-container">Ada {{ $lowStockProducts }} produk dengan stok hampir habis</span>
            @endif
            @if($lowStockProducts > 0 && $expiringProducts > 0)
                <span class="text-on-error-container"> &amp; </span>
            @endif
            @if($expiringProducts > 0)
                <span class="text-on-error-container">{{ $expiringProducts }} produk mendekati kadaluarsa</span>
            @endif
        </div>
        <a href="{{ route('admin.products.index') }}" class="ml-auto text-xs font-bold text-error hover:underline">Lihat Produk &rarr;</a>
    </div>

    @if($lowStockProducts > 0)
    <div class="bg-error-container/60 border border-error/30 rounded-2xl p-5">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-base font-headline font-extrabold text-error flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">error</span>
                Stok Hampir Habis
            </h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-error text-on-error">
                {{ $lowStockProducts }} Produk
            </span>
        </div>
        <p class="text-xs text-on-error-container mt-1.5">Menampilkan 5 produk dengan stok terendah.</p>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            @foreach($lowStockProductList as $product)
            <div class="bg-surface-container-lowest border border-error/20 rounded-xl p-3.5">
                <div class="flex items-start justify-between gap-2">
                    <p class="text-sm font-bold text-on-surface line-clamp-2">{{ $product->name }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-error-container text-error whitespace-nowrap">
                        Stok Hampir Habis
                    </span>
                </div>
                <div class="mt-3 text-xs text-on-surface-variant space-y-1">
                    <p>Stok Saat Ini: <span class="font-bold text-error">{{ $product->stock }}</span></p>
                    <p>Batas Minimum: <span class="font-semibold text-on-surface">{{ $product->min_stock }}</span></p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
        {{-- Total Revenue --}}
        <div class="bg-primary p-6 rounded-2xl text-on-primary flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold opacity-80">Total Pendapatan</p>
                <span class="material-symbols-outlined opacity-60">account_balance_wallet</span>
            </div>
            <div>
                <p class="text-3xl font-headline font-extrabold">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-xs mt-1 opacity-70">Kumulatif seluruh transaksi lunas</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary-container text-base">trending_up</span>
                <span class="text-xs font-semibold text-secondary-container">Aktif</span>
            </div>
        </div>

        {{-- Low Stock --}}
        <div class="bg-error-container p-6 rounded-2xl flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-on-error-container/80">Stok Hampir Habis</p>
                <span class="material-symbols-outlined text-on-error-container/60">inventory_2</span>
            </div>
            <div>
                <p class="text-3xl font-headline font-extrabold text-error">{{ $lowStockProducts }}</p>
                <p class="text-xs mt-1 text-on-error-container/70">Produk di bawah batas minimum</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-error text-base" style="font-variation-settings: 'FILL' 1;">warning</span>
                <span class="text-xs font-semibold text-error">Perlu restok segera</span>
            </div>
        </div>

        {{-- Expiring Products --}}
        <div class="bg-tertiary-fixed p-6 rounded-2xl flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-on-tertiary-fixed/80">Mendekati Kadaluarsa</p>
                <span class="material-symbols-outlined text-on-tertiary-fixed/60">schedule</span>
            </div>
            <div>
                <p class="text-3xl font-headline font-extrabold text-tertiary">{{ $expiringProducts }}</p>
                <p class="text-xs mt-1 text-on-tertiary-fixed/70">Produk kadaluarsa dalam 3 hari</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-tertiary text-base" style="font-variation-settings: 'FILL' 1;">nutrition</span>
                <span class="text-xs font-semibold text-tertiary">Perlu perhatian</span>
            </div>
        </div>
    </div>

    {{-- Main Grid: Charts + Top Products --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- Recent Transactions Table (2/3) --}}
        <div class="xl:col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-outline-variant/10">
                <h2 class="text-lg font-headline font-extrabold text-on-surface">Transaksi Terakhir</h2>
                <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-primary hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                            <th class="px-6 py-3">Invoice</th>
                            <th class="px-6 py-3">Kasir</th>
                            <th class="px-6 py-3">Total</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        @forelse($recentTransactions as $tx)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-primary font-bold">
                                <a href="{{ route('admin.transactions.show', $tx) }}" class="hover:underline">{{ $tx->invoice_number }}</a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-on-primary text-xs font-bold">
                                        {{ strtoupper(substr($tx->user->name ?? '?', 0, 1)) }}
                                    </span>
                                    <span class="font-medium">{{ $tx->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-on-surface">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                @if($tx->status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Lunas</span>
                                @elseif($tx->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-error-container text-error">Batal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $tx->created_at->format('d M, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant text-sm">Belum ada transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Products (1/3) --}}
        @php($topProductsTop3 = $topProducts->take(3))
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-outline-variant/10">
                <h2 class="text-lg font-headline font-extrabold text-on-surface">Produk Terlaris</h2>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-primary hover:underline">Semua Produk</a>
            </div>
            <div class="px-6 pt-5">
                <div class="rounded-2xl border border-outline-variant/10 bg-surface-container-low p-4">
                    <div style="height: 260px;">
                        <canvas id="topProductsChart" class="w-full h-full"></canvas>
                    </div>
                    <p id="topProductsChartFallback" class="hidden text-sm text-on-surface-variant text-center py-12">Chart produk terlaris tidak tersedia</p>
                </div>
            </div>
            <div class="divide-y divide-outline-variant/10">
                @forelse($topProductsTop3 as $index => $product)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-surface-container-low/50 transition-colors">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold font-headline
                        {{ $index === 0 ? 'bg-primary text-on-primary' : ($index === 1 ? 'bg-secondary text-on-secondary' : 'bg-surface-container text-on-surface-variant') }}">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-on-surface truncate">{{ $product->name }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $product->category->name ?? '-' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-bold text-primary">{{ $product->total_sold ?? 0 }} terjual</p>
                        <p class="text-xs text-on-surface-variant">Stok: {{ $product->stock }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-on-surface-variant text-sm">Belum ada data penjualan</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Transaction Line Chart --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-5 border-b border-outline-variant/10 gap-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">show_chart</span>
                <div>
                    <h2 class="text-lg font-headline font-extrabold text-on-surface">Grafik Transaksi</h2>
                    <p class="text-xs text-on-surface-variant mt-0.5">Pendapatan dari transaksi lunas</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 bg-surface-container-low rounded-xl p-1" id="chartFilterGroup">
                <button type="button" data-filter="today"
                    class="chart-filter-btn px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200">
                    Hari Ini
                </button>
                <button type="button" data-filter="week"
                    class="chart-filter-btn px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200">
                    Minggu Ini
                </button>
                <button type="button" data-filter="month"
                    class="chart-filter-btn px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 active">
                    Bulan Ini
                </button>
            </div>
        </div>

        <div class="p-6">
            {{-- Chart Summary Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6" id="chartSummary">
                <div class="bg-surface-container-low rounded-xl p-4">
                    <p class="text-xs text-on-surface-variant font-medium">Total Pendapatan</p>
                    <p class="text-xl font-headline font-extrabold text-primary mt-1" id="chartTotalRevenue">Rp 0</p>
                </div>
                <div class="bg-surface-container-low rounded-xl p-4">
                    <p class="text-xs text-on-surface-variant font-medium">Jumlah Transaksi</p>
                    <p class="text-xl font-headline font-extrabold text-on-surface mt-1" id="chartTotalCount">0</p>
                </div>
                <div class="bg-surface-container-low rounded-xl p-4 hidden sm:block">
                    <p class="text-xs text-on-surface-variant font-medium">Rata-rata / Hari</p>
                    <p class="text-xl font-headline font-extrabold text-secondary mt-1" id="chartAverage">Rp 0</p>
                </div>
            </div>

            {{-- Chart Canvas --}}
            <div class="relative" style="height: 340px;">
                <canvas id="transactionLineChart" class="w-full h-full"></canvas>
                {{-- Loading overlay --}}
                <div id="chartLoading" class="absolute inset-0 flex items-center justify-center bg-surface-container-lowest/80 rounded-xl" style="display:none;">
                    <div class="flex items-center gap-3">
                        <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-semibold text-on-surface-variant">Memuat data...</span>
                    </div>
                </div>
                {{-- Empty state --}}
                <div id="chartEmpty" class="absolute inset-0 flex flex-col items-center justify-center" style="display:none;">
                    <span class="material-symbols-outlined text-on-surface-variant/40 text-5xl mb-3">insert_chart</span>
                    <p class="text-sm text-on-surface-variant font-medium">Belum ada transaksi pada periode ini</p>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── Top Products Bar Chart ───
    const canvas = document.getElementById('topProductsChart');
    const fallback = document.getElementById('topProductsChartFallback');

    if (canvas && typeof Chart !== 'undefined') {
        const labels = [
            @foreach($topProductsTop3 as $product)
                @json($product->name),
            @endforeach
        ];

        const data = [
            @foreach($topProductsTop3 as $product)
                {{ (int) ($product->total_sold ?? 0) }},
            @endforeach
        ];

        if (labels.length && data.length) {
            if (fallback) fallback.classList.add('hidden');

            new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Terjual',
                        data: data,
                        backgroundColor: '#0052cc',
                        borderColor: '#0052cc',
                        borderWidth: 1,
                        borderRadius: 8,
                        barThickness: 22,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return ' Jumlah Terjual: ' + context.parsed.x;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { color: '#667085', maxRotation: 0, autoSkip: false, precision: 0 },
                            grid: { color: 'rgba(0,0,0,0.06)' },
                        },
                        y: {
                            ticks: { color: '#667085' },
                            grid: { display: false },
                        },
                    },
                },
            });
        } else {
            canvas.classList.add('hidden');
            if (fallback) fallback.classList.remove('hidden');
        }
    } else if (canvas) {
        canvas.classList.add('hidden');
        if (fallback) fallback.classList.remove('hidden');
    }

    // ─── Transaction Line Chart ───
    const lineCanvas = document.getElementById('transactionLineChart');
    const chartLoading = document.getElementById('chartLoading');
    const chartEmpty = document.getElementById('chartEmpty');
    const chartTotalRevenue = document.getElementById('chartTotalRevenue');
    const chartTotalCount = document.getElementById('chartTotalCount');
    const chartAverage = document.getElementById('chartAverage');

    if (!lineCanvas || typeof Chart === 'undefined') return;

    let lineChart = null;
    let currentFilter = 'month';

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    async function fetchChartData(filter) {
        chartLoading.style.display = 'flex';
        chartEmpty.style.display = 'none';

        try {
            const response = await fetch(`{{ route('admin.dashboard.chart-data') }}?filter=${filter}`);
            const data = await response.json();

            // Update summary stats
            const totalRev = data.totals.reduce((a, b) => a + b, 0);
            const totalCnt = data.counts.reduce((a, b) => a + b, 0);
            const daysWithData = data.totals.filter(v => v > 0).length || 1;

            chartTotalRevenue.textContent = formatRupiah(totalRev);
            chartTotalCount.textContent = totalCnt + ' transaksi';
            chartAverage.textContent = formatRupiah(Math.round(totalRev / daysWithData));

            // Check if all data is zero
            const hasData = data.totals.some(v => v > 0);

            if (!hasData) {
                chartEmpty.style.display = 'flex';
                if (lineChart) {
                    lineChart.destroy();
                    lineChart = null;
                }
                chartLoading.style.display = 'none';
                return;
            }

            const ctx = lineCanvas.getContext('2d');

            // Create gradient fill
            const gradient = ctx.createLinearGradient(0, 0, 0, 340);
            gradient.addColorStop(0, 'rgba(0, 82, 204, 0.25)');
            gradient.addColorStop(0.5, 'rgba(0, 82, 204, 0.08)');
            gradient.addColorStop(1, 'rgba(0, 82, 204, 0.0)');

            const chartData = {
                labels: data.labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data.totals,
                    borderColor: '#0052cc',
                    backgroundColor: gradient,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0052cc',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#0052cc',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                }],
            };

            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.92)',
                        titleColor: '#fff',
                        bodyColor: '#e5e7eb',
                        titleFont: { size: 13, weight: '700' },
                        bodyFont: { size: 12 },
                        padding: { x: 14, y: 10 },
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            title: function (items) {
                                return items[0].label;
                            },
                            label: function (context) {
                                const idx = context.dataIndex;
                                const total = formatRupiah(context.parsed.y);
                                const count = data.counts[idx] || 0;
                                return [
                                    `💰 Pendapatan: ${total}`,
                                    `📦 Transaksi: ${count}`,
                                ];
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#667085',
                            font: { size: 11, weight: '500' },
                            maxRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: filter === 'today' ? 12 : 15,
                        },
                        grid: { display: false },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#667085',
                            font: { size: 11 },
                            callback: function (value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(1) + ' jt';
                                if (value >= 1000) return (value / 1000).toFixed(0) + ' rb';
                                return value;
                            },
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false,
                        },
                    },
                },
                animation: {
                    duration: 800,
                    easing: 'easeOutQuart',
                },
            };

            if (lineChart) {
                lineChart.data = chartData;
                lineChart.options = chartOptions;
                lineChart.update('active');
            } else {
                lineChart = new Chart(lineCanvas, {
                    type: 'line',
                    data: chartData,
                    options: chartOptions,
                });
            }
        } catch (err) {
            console.error('Chart data fetch failed:', err);
            chartEmpty.style.display = 'flex';
        } finally {
            chartLoading.style.display = 'none';
        }
    }

    // Filter button click handlers
    document.querySelectorAll('.chart-filter-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.chart-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            fetchChartData(currentFilter);
        });
    });

    // Initial load
    fetchChartData(currentFilter);
});
</script>

<style>
    .chart-filter-btn {
        color: var(--md-sys-color-on-surface-variant, #49454F);
        background: transparent;
    }
    .chart-filter-btn:hover {
        background: rgba(0, 0, 0, 0.05);
    }
    .chart-filter-btn.active {
        background: var(--md-sys-color-primary, #0052cc);
        color: var(--md-sys-color-on-primary, #fff);
        box-shadow: 0 2px 8px rgba(0, 82, 204, 0.25);
    }
</style>
@endsection
