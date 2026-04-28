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
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-5 border-b border-outline-variant/10">
                <h2 class="text-lg font-headline font-extrabold text-on-surface">Produk Terlaris</h2>
                <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-primary hover:underline">Semua Produk</a>
            </div>
            <div class="divide-y divide-outline-variant/10">
                @forelse($topProducts as $index => $product)
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

</div>
@endsection
