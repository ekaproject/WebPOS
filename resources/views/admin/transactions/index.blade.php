@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Riwayat Transaksi</h1>
            <p class="text-on-surface-variant mt-1">Semua transaksi penjualan toko</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-4">
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Cari Invoice</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="No. Invoice..."
                           class="pl-10 w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Status</label>
                <select name="status" class="rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary">
                    <option value="">Semua</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Dari Tanggal</label>
                <input type="date" name="from" value="{{ request('from') }}"
                       class="rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Sampai Tanggal</label>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl font-bold text-sm">Filter</button>
            <a href="{{ route('admin.transactions.index') }}" class="px-5 py-2 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Invoice</th>
                        <th class="px-6 py-3">Kasir</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Dibayar</th>
                        <th class="px-6 py-3">Metode</th>
                        <th class="px-6 py-3">Terminal</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($transactions as $tx)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-primary font-bold">{{ $tx->invoice_number }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-on-primary text-xs font-bold">
                                    {{ strtoupper(substr($tx->user->name ?? '?', 0, 1)) }}
                                </span>
                                <span class="font-medium">{{ $tx->user->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-on-surface-variant">Rp {{ number_format($tx->paid_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="capitalize px-2.5 py-1 rounded-full text-xs font-bold bg-primary-fixed text-primary">{{ $tx->payment_method }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs font-mono text-on-surface-variant">{{ $tx->cashier_terminal ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($tx->status === 'paid')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Lunas</span>
                            @elseif($tx->status === 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">Pending</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-error-container text-error">Batal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $tx->created_at->format('d M Y, H:i') }}</td>
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
                        <td colspan="9" class="px-6 py-16 text-center">
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
