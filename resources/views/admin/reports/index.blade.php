@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Form Laporan Transaksi</h1>
            <p class="text-on-surface-variant mt-1">Filter periode lalu tinjau ringkasan transaksi lunas secara cepat.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.reports.export.pdf', request()->only(['from', 'to'])) }}"
               class="px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-primary hover:brightness-110 transition-all">
                Export PDF
            </a>
            <a href="{{ route('admin.reports.export.excel', request()->only(['from', 'to'])) }}"
               class="px-4 py-2.5 rounded-xl text-sm font-bold text-white"
               style="background: linear-gradient(135deg, #0369A1 0%, #16a34a 100%);">
                Export Excel
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.reports.index') }}" class="bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="from">Dari Tanggal</label>
                <input id="from" type="date" name="from" value="{{ $filters['from'] }}"
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="to">Sampai Tanggal</label>
                <input id="to" type="date" name="to" value="{{ $filters['to'] }}"
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-end gap-2 md:justify-end">
                <button type="submit" class="w-full sm:w-auto md:w-full px-4 py-2.5 rounded-xl text-sm font-bold text-white"
                        style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
                    Tampilkan Laporan
                </button>
                <a href="{{ route('admin.reports.index') }}" class="w-full sm:w-auto px-4 py-2.5 rounded-xl text-sm font-bold bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors text-center">Reset</a>
            </div>
        </div>
        @error('from')<p class="text-error text-xs mt-2">{{ $message }}</p>@enderror
        @error('to')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        <div class="bg-primary text-on-primary rounded-2xl p-5">
            <p class="text-xs opacity-80 uppercase tracking-wider">Total Data</p>
            <p class="text-3xl font-extrabold mt-1">{{ number_format($summary['count']) }}</p>
        </div>
        <div class="bg-secondary text-on-secondary rounded-2xl p-5">
            <p class="text-xs opacity-80 uppercase tracking-wider">Pendapatan Lunas</p>
            <p class="text-2xl font-extrabold mt-1">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl p-5" style="background: linear-gradient(135deg, #16a34a, #15803d); color:#fff;">
            <p class="text-xs opacity-80 uppercase tracking-wider">Total Laba</p>
            <p class="text-2xl font-extrabold mt-1">Rp {{ number_format($summary['profit'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/10 flex items-center justify-between">
            <h2 class="text-lg font-headline font-extrabold">Detail Laporan</h2>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-primary/10 text-primary">{{ $transactions->total() }} transaksi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Invoice</th>
                        <th class="px-6 py-3">Kasir</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Laba</th>
                        <th class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($transactions as $transaction)
                    @php
                        $costOfGoods = $transaction->items->sum(fn ($item) => ($item->product->purchase_price ?? 0) * $item->quantity);
                        $laba = $transaction->total_amount - $costOfGoods;
                    @endphp
                    <tr>
                        <td class="px-6 py-4 font-mono text-xs text-primary font-bold">{{ $transaction->invoice_number }}</td>
                        <td class="px-6 py-4">{{ $transaction->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 font-bold {{ $laba >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            Rp {{ number_format($laba, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-on-surface-variant">Tidak ada data sesuai filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-outline-variant/10">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection
