@extends('layouts.admin')

@section('title', 'Detail Barang Masuk')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.inbound-items.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-headline font-extrabold text-primary">{{ $inboundItem->product_name }}</h1>
            <p class="text-on-surface-variant mt-0.5">Distributor: {{ $inboundItem->distributor->name }}</p>
        </div>
        @if($inboundItem->qc_status === 'completed')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">QC Completed</span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">QC Pending</span>
        @endif
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
        @if($inboundItem->product_photo)
            <div class="mb-4">
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">Foto Produk</p>
                <img src="{{ asset('storage/'.$inboundItem->product_photo) }}" alt="{{ $inboundItem->product_name }}"
                     class="w-40 h-40 rounded-xl border border-outline-variant/20 object-cover" />
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Kategori</p>
                <p class="font-semibold text-on-surface">{{ $inboundItem->category->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Ukuran Produk</p>
                <p class="font-semibold text-on-surface">{{ $inboundItem->ukuran_produk ?: '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Harga Beli</p>
                <p class="font-semibold text-on-surface">Rp {{ number_format((float) $inboundItem->purchase_price, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Harga Jual</p>
                <p class="font-semibold text-on-surface">Rp {{ number_format((float) $inboundItem->selling_price, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Jumlah Inbound</p>
                <p class="font-bold text-on-surface">{{ $inboundItem->quantity_inbound }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Tanggal Masuk</p>
                <p class="font-semibold text-on-surface">{{ $inboundItem->inbound_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Expired Date</p>
                <p class="font-semibold text-on-surface">{{ $inboundItem->expired_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Status QC</p>
                <p class="font-semibold text-on-surface">{{ strtoupper($inboundItem->qc_status) }}</p>
            </div>
        </div>
        @if($inboundItem->note)
            <div class="mt-4 pt-4 border-t border-outline-variant/10 text-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Catatan</p>
                <p class="text-on-surface">{{ $inboundItem->note }}</p>
            </div>
        @endif
    </div>

    @if($inboundItem->qc_status === 'pending')
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
        <h2 class="text-lg font-headline font-extrabold text-on-surface mb-1">Proses Quality Control</h2>
        <p class="text-xs text-on-surface-variant mb-4">Pastikan jumlah barang baik + rusak sama dengan {{ $inboundItem->quantity_inbound }}.</p>

        <form action="{{ route('admin.inbound-items.qc.process', $inboundItem) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="good_qty" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Barang Baik</label>
                    <input type="number" id="good_qty" name="good_qty" min="0" value="{{ old('good_qty', $inboundItem->quantity_inbound) }}" required
                           class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('good_qty') border-error @enderror"/>
                    @error('good_qty')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="damaged_qty" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Barang Rusak</label>
                    <input type="number" id="damaged_qty" name="damaged_qty" min="0" value="{{ old('damaged_qty', 0) }}" required
                           class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('damaged_qty') border-error @enderror"/>
                    @error('damaged_qty')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            @error('qc_status')<p class="text-error text-xs">{{ $message }}</p>@enderror

            <button type="submit" class="px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Hasil QC
            </button>
        </form>
    </div>
    @else
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
            <h2 class="text-lg font-headline font-extrabold text-on-surface mb-4">Ringkasan QC</h2>
            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-on-surface-variant">Barang Baik</span>
                    <span class="font-bold text-secondary">{{ $inboundItem->qcItem?->good_qty ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-on-surface-variant">Barang Rusak</span>
                    <span class="font-bold text-error">{{ $inboundItem->qcItem?->damaged_qty ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-outline-variant/10">
                    <span class="text-on-surface-variant">Waktu Cek</span>
                    <span class="font-semibold">{{ optional($inboundItem->qcItem?->checked_at)->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
            <h2 class="text-lg font-headline font-extrabold text-on-surface mb-4">Hasil Otomatis</h2>
            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Produk Aktif Dibuat</p>
                    <p class="font-semibold">{{ $inboundItem->products->count() }} batch</p>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Data Retur Dibuat</p>
                    <p class="font-semibold">{{ $inboundItem->returns->count() }} data</p>
                </div>
                @if($inboundItem->returns->isNotEmpty())
                    <div class="pt-2 border-t border-outline-variant/10">
                        @foreach($inboundItem->returns as $return)
                            <a href="{{ route('admin.returns.show', $return) }}" class="text-primary text-xs font-bold hover:underline block">
                                Retur #{{ $return->id }} - {{ $return->qty }} unit ({{ $return->status }})
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
