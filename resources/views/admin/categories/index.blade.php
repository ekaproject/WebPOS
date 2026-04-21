@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Manajemen Kategori</h1>
            <p class="text-on-surface-variant mt-1">Kelola kategori produk toko Anda</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
           style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">add</span>
            Tambah Kategori
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-secondary-container text-on-secondary-container px-5 py-3 rounded-xl flex items-center gap-2 font-medium text-sm">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-error-container text-on-error-container px-5 py-3 rounded-xl flex items-center gap-2 font-medium text-sm">
            <span class="material-symbols-outlined">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($categories as $category)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-xl">{{ $category->icon }}</span>
                                </div>
                                <div>
                                    <p class="font-bold">{{ $category->name }}</p>
                                    @if($category->description)
                                        <p class="text-xs text-on-surface-variant">{{ $category->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-bold">{{ $category->products_count }}</td>
                        <td class="px-6 py-4">
                            @if($category->is_active)
                                <span class="flex items-center gap-1 text-secondary text-xs font-bold">
                                    <span class="material-symbols-outlined text-base">check_circle</span> Aktif
                                </span>
                            @else
                                <span class="flex items-center gap-1 text-on-surface-variant text-xs font-bold">
                                    <span class="material-symbols-outlined text-base">cancel</span> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="p-2 rounded-xl bg-primary-fixed hover:bg-primary hover:text-on-primary text-primary transition-colors"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <a href="{{ route('categories.show', $category->slug) }}" target="_blank"
                                   class="p-2 rounded-xl bg-surface-container-high hover:bg-surface-container-highest text-on-surface-variant transition-colors"
                                   title="Lihat di publik">
                                    <span class="material-symbols-outlined text-base">open_in_new</span>
                                </a>
                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                      onsubmit="return confirm('Hapus kategori {{ $category->name }}? Kategori yang memiliki produk tidak bisa dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-2 rounded-xl bg-error-container hover:bg-error hover:text-on-error text-on-error-container transition-colors"
                                            title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl block mb-2">category</span>
                            Belum ada kategori. <a href="{{ route('admin.categories.create') }}" class="text-primary font-bold">Tambah sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/10">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
