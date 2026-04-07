@extends('layouts.admin')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.transactions.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Detail Transaksi</h1>
            <p class="text-on-surface-variant font-mono text-sm mt-0.5">{{ $transaction->invoice_number }}</p>
        </div>
        <div class="ml-auto">
            @if($transaction->status === 'paid')
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold bg-secondary-container text-on-secondary-container">
                    <span class="material-symbols-outlined text-base" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                    Lunas
                </span>
            @elseif($transaction->status === 'pending')
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold bg-tertiary-fixed text-tertiary">
                    <span class="material-symbols-outlined text-base">schedule</span>
                    Pending
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-bold bg-error-container text-error">
                    <span class="material-symbols-outlined text-base">cancel</span>
                    Dibatalkan
                </span>
            @endif
        </div>
    </div>

    {{-- Transaction Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6 space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Informasi Transaksi</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-on-surface-variant">No. Invoice</dt>
                    <dd class="font-mono font-bold text-primary">{{ $transaction->invoice_number }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-on-surface-variant">Tanggal</dt>
                    <dd class="font-semibold">{{ $transaction->created_at->format('d M Y, H:i') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-on-surface-variant">Kasir</dt>
                    <dd class="font-semibold">{{ $transaction->user->name ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-on-surface-variant">Terminal</dt>
                    <dd class="font-mono font-semibold">{{ $transaction->cashier_terminal ?? '-' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-on-surface-variant">Metode Bayar</dt>
                    <dd class="capitalize font-semibold">{{ $transaction->payment_method }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-primary rounded-2xl p-6 text-on-primary space-y-4">
            <h2 class="text-xs font-bold uppercase tracking-wider opacity-70">Ringkasan Pembayaran</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="opacity-70">Total Belanja</dt>
                    <dd class="font-bold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="opacity-70">Dibayar</dt>
                    <dd class="font-bold">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</dd>
                </div>
                @if($transaction->paid_amount >= $transaction->total_amount)
                <div class="flex justify-between border-t border-white/20 pt-3">
                    <dt class="opacity-70">Kembalian</dt>
                    <dd class="font-extrabold text-secondary-container text-lg">
                        Rp {{ number_format($transaction->paid_amount - $transaction->total_amount, 0, ',', '.') }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="px-6 py-5 border-b border-outline-variant/10">
            <h2 class="text-lg font-headline font-extrabold text-on-surface">Item Transaksi</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                    <th class="px-6 py-3">Produk</th>
                    <th class="px-6 py-3 text-right">Harga Satuan</th>
                    <th class="px-6 py-3 text-right">Qty</th>
                    <th class="px-6 py-3 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant/10">
                @foreach($transaction->items as $item)
                <tr>
                    <td class="px-6 py-4">
                        <p class="font-bold text-on-surface">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $item->product->sku ?? '-' }}</p>
                    </td>
                    <td class="px-6 py-4 text-right text-on-surface-variant">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-right font-bold">{{ $item->quantity }}</td>
                    <td class="px-6 py-4 text-right font-bold text-on-surface">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-surface-container-low">
                <tr>
                    <td colspan="3" class="px-6 py-4 text-right font-extrabold text-on-surface text-base font-headline">Total</td>
                    <td class="px-6 py-4 text-right font-extrabold text-primary text-lg font-headline">
                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>
@endsection
