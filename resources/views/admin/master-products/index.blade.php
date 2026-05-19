@extends('layouts.admin')

@section('title', 'Master Produk')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Master Produk</h1>
            <p class="text-on-surface-variant mt-1">Katalog produk acuan untuk input barang masuk</p>
        </div>
        <a href="{{ route('admin.master-products.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
           style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">add</span>
            Tambah Master Produk
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-secondary-container text-on-secondary-container px-5 py-3 rounded-xl flex items-center gap-2 font-medium text-sm">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px] max-w-md flex items-center gap-2 border border-outline-variant/30 rounded-xl px-4 bg-white">
            <span class="material-symbols-outlined text-on-surface-variant text-xl">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full h-11 bg-transparent text-sm focus:outline-none"
                   placeholder="Cari nama produk..."/>
        </div>
        <select name="category" class="h-11 px-4 rounded-xl border border-outline-variant/30 bg-white text-sm">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold">Cari</button>
        @if(request('search') || request('category'))
            <a href="{{ route('admin.master-products.index') }}" class="px-5 py-2.5 rounded-xl bg-surface-container text-on-surface-variant text-sm font-bold">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Barcode</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Ukuran</th>
                        <th class="px-6 py-3">Satuan</th>
                        <th class="px-6 py-3">Total Inbound</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($masterProducts as $mp)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($mp->image)
                                    <img src="{{ asset('storage/' . $mp->image) }}" alt="{{ $mp->name }}"
                                         class="w-10 h-10 rounded-xl object-cover border border-outline-variant/20">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary text-xl">inventory_2</span>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold">{{ $mp->name }}</p>
                                    @if($mp->description)
                                        <p class="text-xs text-on-surface-variant line-clamp-1">{{ $mp->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="space-y-2">
                                <div class="inline-flex items-center justify-center rounded-lg bg-white p-2 border border-outline-variant/20">
                                    {!! $mp->barcode_svg !!}
                                </div>
                                <p class="text-[11px] font-mono text-on-surface-variant">{{ $mp->barcode_value }}</p>
                                <a href="{{ route('admin.master-products.barcode', $mp) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-on-secondary-container bg-secondary-container hover:opacity-90 transition-colors">
                                    <span class="material-symbols-outlined text-sm">print</span>
                                    Print
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-on-surface-variant">{{ $mp->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-on-surface-variant">{{ $mp->ukuran ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($mp->satuan)
                                <span class="inline-block px-3 py-1 rounded-full bg-primary-fixed text-primary text-xs font-bold">
                                    {{ $mp->satuan->singkatan }}
                                </span>
                            @else
                                <span class="text-on-surface-variant text-xs">{{ $mp->unit ?? '-' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-primary">{{ $mp->inbound_items_count ?? $mp->inboundItems()->count() }}</td>
                        <td class="px-6 py-4">
                            @if($mp->is_active)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-secondary bg-secondary-container px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary inline-block"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-error bg-error-container px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-error inline-block"></span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.master-products.edit', $mp) }}"
                                   class="p-2 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-on-surface-variant"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form action="{{ route('admin.master-products.destroy', $mp) }}" method="POST"
                                      onsubmit="return confirm('Hapus master produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg bg-error-container hover:bg-error/20 transition-colors text-error"
                                            title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2 opacity-40">inventory_2</span>
                            <p class="font-medium">Belum ada master produk</p>
                            <p class="text-xs mt-1">Tambahkan produk agar bisa digunakan saat input barang masuk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($masterProducts->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/10">
                {{ $masterProducts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
