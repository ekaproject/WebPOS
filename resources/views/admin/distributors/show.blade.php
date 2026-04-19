@extends('layouts.admin')

@section('title', 'Detail Distributor')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.distributors.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-headline font-extrabold text-primary">{{ $distributor->name }}</h1>
            <p class="text-on-surface-variant mt-0.5 font-mono text-sm">{{ $distributor->code }}</p>
        </div>
        <a href="{{ route('admin.distributors.edit', $distributor) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-on-primary"
           style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-lg">edit</span>
            Edit
        </a>
    </div>

    {{-- Info Card --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8">
        <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">business</span>
            Informasi Distributor
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Kontak Person</p>
                <p class="text-sm font-medium">{{ $distributor->contact_person ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">No. Telepon</p>
                <p class="text-sm font-medium">{{ $distributor->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Email</p>
                <p class="text-sm font-medium">{{ $distributor->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Status</p>
                @if($distributor->is_active)
                    <span class="inline-flex items-center gap-1 text-secondary text-sm font-bold">
                        <span class="material-symbols-outlined text-base">check_circle</span> Aktif
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-on-surface-variant text-sm font-bold">
                        <span class="material-symbols-outlined text-base">cancel</span> Nonaktif
                    </span>
                @endif
            </div>
            <div class="md:col-span-2">
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1">Alamat</p>
                <p class="text-sm font-medium">{{ $distributor->address ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="px-8 pt-6 pb-4">
            <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">inventory_2</span>
                Produk yang Disupply
                <span class="ml-2 px-2.5 py-0.5 rounded-full bg-primary-fixed text-primary text-xs font-bold">
                    {{ $distributor->products->count() }}
                </span>
            </h2>
        </div>

        @if($distributor->products->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">SKU</th>
                        <th class="px-6 py-3 text-right">Harga Beli</th>
                        <th class="px-6 py-3 text-right">Harga Jual</th>
                        <th class="px-6 py-3 text-right">Margin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($distributor->products as $product)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4 font-bold">{{ $product->name }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-on-surface-variant">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($product->pivot->purchase_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-right">
                            @php
                                $margin = $product->price - $product->pivot->purchase_price;
                                $marginPct = $product->pivot->purchase_price > 0 ? round(($margin / $product->pivot->purchase_price) * 100, 1) : 0;
                            @endphp
                            <span class="{{ $margin >= 0 ? 'text-secondary' : 'text-error' }} font-bold">
                                Rp {{ number_format($margin, 0, ',', '.') }}
                                <span class="text-xs font-normal">({{ $marginPct }}%)</span>
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-8 pb-8 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-4xl block mb-2">inventory_2</span>
            <p>Belum ada produk yang ditautkan.</p>
            <a href="{{ route('admin.distributors.edit', $distributor) }}" class="text-primary font-bold text-sm">Tambah produk →</a>
        </div>
        @endif
    </div>
</div>
@endsection
