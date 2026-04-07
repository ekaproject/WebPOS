@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Manajemen Produk</h1>
            <p class="text-on-surface-variant mt-1">Kelola semua produk toko Anda</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
           style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">add</span>
            Tambah Produk
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-4">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Cari Produk</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama atau SKU..."
                           class="pl-10 w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Kategori</label>
                <select name="category" class="rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary">
                    <option value="">Semua</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Stok</label>
                <select name="stock" class="rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary">
                    <option value="">Semua</option>
                    <option value="low" {{ request('stock') === 'low' ? 'selected' : '' }}>Stok Kritis</option>
                    <option value="ok" {{ request('stock') === 'ok' ? 'selected' : '' }}>Stok Aman</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl font-bold text-sm">Filter</button>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">SKU</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Harga</th>
                        <th class="px-6 py-3">Stok</th>
                        <th class="px-6 py-3">Kadaluarsa</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($products as $product)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-fixed overflow-hidden flex items-center justify-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover"/>
                                    @else
                                        <span class="material-symbols-outlined text-primary text-lg">inventory_2</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-on-surface">{{ $product->name }}</p>
                                    <p class="text-xs text-on-surface-variant">{{ $product->unit }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-on-surface-variant">{{ $product->sku }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-primary-fixed text-primary">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-on-surface">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($product->isLowStock())
                                <span class="flex items-center gap-1 text-error font-bold">
                                    <span class="material-symbols-outlined text-base">warning</span>
                                    {{ $product->stock }}
                                </span>
                            @else
                                <span class="text-on-surface font-semibold">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-on-surface-variant">
                            {{ $product->expires_at ? $product->expires_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-surface-container text-on-surface-variant">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="p-2 rounded-lg bg-primary-fixed text-primary hover:bg-primary hover:text-on-primary transition-colors"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                      onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-lg bg-error-container text-error hover:bg-error hover:text-on-error transition-colors"
                                            title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-16 text-center">
                            <span class="material-symbols-outlined text-5xl text-on-surface-variant/30 block mb-3">inventory_2</span>
                            <p class="text-on-surface-variant font-medium">Belum ada produk</p>
                            <a href="{{ route('admin.products.create') }}" class="text-primary font-bold text-sm mt-2 inline-block hover:underline">+ Tambah Produk Pertama</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant/10">
            {{ $products->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
