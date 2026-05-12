@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Riwayat Transaksi</h1>
            <p class="text-on-surface-variant mt-1">Cari dan filter transaksi penjualan toko</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.transactions.export.pdf', request()->query()) }}" 
               class="px-4 py-2.5 rounded-xl text-sm font-bold text-white bg-error hover:brightness-110 transition-all">
                Export PDF
            </a>
            <a href="{{ route('admin.transactions.export.excel', request()->query()) }}" 
               class="px-4 py-2.5 rounded-xl text-sm font-bold text-white"
               style="background: linear-gradient(135deg, #16a34a 0%, #0369A1 100%);">
                Export Excel
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form action="{{ route('admin.transactions.index') }}" method="GET" class="bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-4">
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Cari Invoice</label>
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-on-surface-variant">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="No. Invoice..."
                           class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Status</label>
                <select name="status" class="h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl font-bold text-sm">Filter</button>
            <a href="{{ route('admin.transactions.index') }}" class="px-5 py-2 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/10 flex items-center justify-between">
            <h2 class="text-lg font-headline font-extrabold">Detail Transaksi</h2>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-primary/10 text-primary">{{ $transactions->total() }} transaksi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Invoice</th>
                        <th class="px-6 py-3">Kasir</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Dibayar</th>
                        <th class="px-6 py-3">Metode</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-primary font-bold">{{ $tx->invoice_number }}</td>
                        <td class="px-6 py-4">{{ $tx->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-on-surface-variant">Rp {{ number_format($tx->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="capitalize px-2 py-1 rounded-full text-xs font-bold bg-primary-fixed text-primary">{{ $tx->payment_method }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($tx->status === 'paid')
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Lunas</span>
                            @elseif($tx->status === 'pending')
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">Pending</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold bg-error-container text-error">Batal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $tx->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.transactions.show', $tx) }}"
                               class="p-2 rounded-lg bg-primary-fixed text-primary hover:bg-primary hover:text-on-primary transition-colors inline-flex"
                               title="Detail">
                                <span class="material-symbols-outlined text-base">visibility</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 block mb-3">receipt_long</span>
                            <p class="text-on-surface-variant font-medium">Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant/10">
            {{ $transactions->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
