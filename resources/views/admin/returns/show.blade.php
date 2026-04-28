@extends('layouts.admin')

@section('title', 'Detail Return')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.returns.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-headline font-extrabold text-primary">Return #{{ $inventoryReturn->id }}</h1>
            <p class="text-on-surface-variant mt-0.5">{{ $inventoryReturn->product_name }} - {{ $inventoryReturn->distributor->name }}</p>
        </div>
        @if($inventoryReturn->status === 'completed')
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Completed</span>
        @else
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">Pending</span>
        @endif
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Distributor</p>
                <p class="font-semibold">{{ $inventoryReturn->distributor->name }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Qty Rusak</p>
                <p class="font-bold text-error">{{ $inventoryReturn->qty }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Status</p>
                <p class="font-semibold uppercase">{{ $inventoryReturn->status }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Inbound Asal</p>
                <a href="{{ route('admin.inbound-items.show', $inventoryReturn->inboundItem) }}" class="text-primary text-sm font-bold hover:underline">
                    Inbound #{{ $inventoryReturn->inbound_item_id }}
                </a>
            </div>
        </div>
        @if($inventoryReturn->note)
            <div class="mt-4 pt-4 border-t border-outline-variant/10 text-sm">
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Catatan</p>
                <p>{{ $inventoryReturn->note }}</p>
            </div>
        @endif
    </div>

    @if($inventoryReturn->status === 'pending')
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
        <h2 class="text-lg font-headline font-extrabold text-on-surface mb-1">Selesaikan Return</h2>
        <p class="text-xs text-on-surface-variant mb-4">Input barang pengganti yang diterima dari distributor. Data akan dibuat sebagai batch produk baru.</p>

        <form action="{{ route('admin.returns.complete', $inventoryReturn) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="replacement_qty" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Jumlah Barang Pengganti <span class="text-error">*</span>
                    </label>
                          <input type="number" id="replacement_qty" name="replacement_qty" min="1" value="{{ old('replacement_qty', $inventoryReturn->qty) }}" required
                              class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('replacement_qty') border-error @enderror"/>
                    @error('replacement_qty')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="replacement_expired_date" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Expired Date Pengganti <span class="text-error">*</span>
                    </label>
                          <input type="date" id="replacement_expired_date" name="replacement_expired_date" value="{{ old('replacement_expired_date') }}" required
                              class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('replacement_expired_date') border-error @enderror"/>
                    @error('replacement_expired_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <button type="submit" class="px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Selesaikan Return
            </button>
        </form>
    </div>
    @else
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
        <h2 class="text-lg font-headline font-extrabold text-on-surface mb-4">Barang Pengganti Tersimpan</h2>
        <p class="text-sm text-on-surface-variant mb-4">Waktu selesai: {{ optional($inventoryReturn->resolved_at)->format('d M Y H:i') }}</p>

        @if($inventoryReturn->replacementProducts->isNotEmpty())
            <div class="space-y-3">
                @foreach($inventoryReturn->replacementProducts as $product)
                    <div class="p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-sm flex items-center justify-between">
                        <div>
                            <p class="font-bold text-on-surface">{{ $product->name }}</p>
                            <p class="text-xs text-on-surface-variant">SKU: {{ $product->sku }} | Exp: {{ optional($product->expires_at)->format('d M Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-on-surface-variant">Stock</p>
                            <p class="font-bold text-secondary">{{ $product->stock }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-on-surface-variant">Belum ada batch produk pengganti yang terdata.</p>
        @endif
    </div>
    @endif
</div>
@endsection
