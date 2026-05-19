@extends('layouts.admin')

@section('title', 'Cetak Barcode')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4 no-print">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Cetak Barcode</h1>
            <p class="text-on-surface-variant mt-1">Barcode produk untuk diprint atau ditempel pada label.</p>
        </div>
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-on-primary"
                style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">print</span>
            Print
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-outline-variant/20 p-8 shadow-sm print:shadow-none">
        <div class="text-center space-y-3">
            <p class="text-xs uppercase tracking-[0.3em] text-on-surface-variant font-bold">{{ $product->category->name ?? 'Produk POS' }}</p>
            <h2 class="text-2xl font-extrabold text-on-surface">{{ $product->name }}</h2>
            <p class="text-sm text-on-surface-variant">Kode Produk: <span class="font-mono font-bold">{{ $product->barcode_value }}</span></p>
        </div>

        <div class="my-8 flex justify-center">
            <div class="bg-white p-4 border border-outline-variant/20 rounded-2xl">
                {!! $product->barcode_svg !!}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="bg-surface-container-low rounded-xl p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">SKU</p>
                <p class="font-mono text-on-surface">{{ $product->sku }}</p>
            </div>
            <div class="bg-surface-container-low rounded-xl p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Satuan</p>
                <p class="text-on-surface">{{ $product->unit }}</p>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-on-surface-variant">
            Gunakan barcode ini untuk scan saat transaksi kasir.
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: #fff; }
        .no-print { display: none !important; }
        .print\:shadow-none { box-shadow: none !important; }
        .max-w-3xl { max-width: none !important; }
    }
</style>
@endsection