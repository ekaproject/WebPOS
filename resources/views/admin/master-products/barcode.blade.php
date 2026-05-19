@extends('layouts.admin')

@section('title', 'Cetak Barcode Master Produk')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4 no-print">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Cetak Barcode Master Produk</h1>
            <p class="text-on-surface-variant mt-1">Layout label sederhana untuk stiker dan kebutuhan gudang.</p>
        </div>
        <button type="button" onclick="window.print()"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-on-primary"
                style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">print</span>
            Print
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 print:grid-cols-2">
        @for ($i = 0; $i < 6; $i++)
            <div class="barcode-label bg-white border border-outline-variant/20 rounded-2xl p-4 text-center shadow-sm print:shadow-none">
                <p class="text-[10px] uppercase tracking-[0.25em] text-on-surface-variant font-bold">Master Produk</p>
                <h2 class="text-lg font-extrabold text-on-surface mt-1 leading-tight">{{ $masterProduct->name }}</h2>
                <p class="text-xs text-on-surface-variant mt-1">{{ $masterProduct->ukuran ?: '-' }} | {{ $masterProduct->satuan->singkatan ?? $masterProduct->unit ?? '-' }}</p>
                <div class="my-3 flex justify-center">
                    <div class="bg-white p-2 rounded-xl border border-outline-variant/20">
                        {!! $masterProduct->barcode_svg !!}
                    </div>
                </div>
                <p class="font-mono text-sm font-bold text-on-surface">{{ $masterProduct->barcode_value }}</p>
            </div>
        @endfor
    </div>
</div>

<style>
    @media print {
        body { background: #fff; }
        .no-print { display: none !important; }
        .print\:shadow-none { box-shadow: none !important; }
        .print\:grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
        .barcode-label { break-inside: avoid; page-break-inside: avoid; }
        .max-w-4xl { max-width: none !important; }
    }
</style>
@endsection