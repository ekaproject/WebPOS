@extends('layouts.admin')

@section('title', 'Detail Produk')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-highest transition-colors text-on-surface-variant">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-headline font-extrabold text-primary">Detail Produk</h1>
                <p class="text-on-surface-variant mt-1">{{ $product->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.edit', $product) }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-primary bg-primary-fixed hover:bg-primary hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-xl">edit</span>
                Edit Produk
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                  onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-sm text-on-error-container bg-error-container hover:bg-error hover:text-on-error transition-colors">
                    <span class="material-symbols-outlined text-xl">delete</span>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Content --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Gambar --}}
        <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6">
            <div class="w-full aspect-square rounded-xl bg-primary-fixed overflow-hidden flex items-center justify-center mb-4">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover"/>
                @else
                    <span class="material-symbols-outlined text-primary text-6xl">inventory_2</span>
                @endif
            </div>
            
            <div class="text-center">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold {{ $product->is_active ? 'bg-secondary-container text-on-secondary-container' : 'bg-surface-container text-on-surface-variant' }}">
                    {{ $product->is_active ? '• Aktif' : '• Nonaktif' }}
                </span>
            </div>
        </div>

        {{-- Detail Informasi --}}
        <div class="md:col-span-2 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6 space-y-6">
            <div>
                <h3 class="text-lg font-bold text-on-surface mb-4">Informasi Utama</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Nama Produk</p>
                        <p class="font-medium text-on-surface">{{ $product->name }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">SKU</p>
                        <p class="font-mono font-medium text-on-surface">{{ $product->sku }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Kategori</p>
                        <p class="font-medium text-on-surface">{{ $product->category->name ?? '-' }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Harga Beli</p>
                        <p class="font-bold text-on-surface text-lg">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Harga Jual</p>
                        <p class="font-bold text-primary text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/20">

            <div>
                <h3 class="text-lg font-bold text-on-surface mb-4">Inventaris & Satuan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Stok Saat Ini</p>
                        <p class="font-medium text-on-surface flex items-center gap-2">
                            {{ $product->stock }}
                            @if($product->isLowStock())
                                <span class="material-symbols-outlined text-error text-lg" title="Stok Kritis">warning</span>
                            @endif
                        </p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Minimal Stok</p>
                        <p class="font-medium text-on-surface">{{ $product->min_stock ?? '-' }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Satuan</p>
                        <p class="font-medium text-on-surface">{{ ucfirst($product->unit) }}</p>
                    </div>
                    <div class="bg-surface-container-low rounded-xl p-4">
                        <p class="text-xs text-on-surface-variant font-bold uppercase tracking-wider mb-1">Kadaluarsa</p>
                        <p class="font-medium text-on-surface">{{ $product->expires_at ? $product->expires_at->format('d M Y') : 'Tidak ada' }}</p>
                    </div>
                </div>
            </div>

            <hr class="border-outline-variant/20">

            <div>
                <h3 class="text-lg font-bold text-on-surface mb-2">Deskripsi Produk</h3>
                <div class="bg-surface-container-low rounded-xl p-4 prose prose-sm max-w-none text-on-surface">
                    @if($product->description)
                        {!! nl2br(e($product->description)) !!}
                    @else
                        <p class="text-on-surface-variant italic">Tidak ada deskripsi</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection