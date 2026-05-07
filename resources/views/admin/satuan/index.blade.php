@extends('layouts.admin')

@section('title', 'Master Satuan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Master Satuan</h1>
            <p class="text-on-surface-variant mt-1">Kelola daftar satuan yang digunakan pada master produk</p>
        </div>
        <a href="{{ route('admin.satuan.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
           style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">add</span>
            Tambah Satuan
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
                        <th class="px-6 py-3">Nama Satuan</th>
                        <th class="px-6 py-3">Singkatan</th>
                        <th class="px-6 py-3 text-center">Dipakai di Master Produk</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($satuanList as $item)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4 font-semibold">{{ $item->nama }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 rounded-full bg-primary-fixed text-primary text-xs font-bold uppercase">
                                {{ $item->singkatan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $item->master_products_count > 0 ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
                                {{ $item->master_products_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.satuan.edit', $item) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-surface-container text-on-surface text-xs font-bold hover:bg-surface-container-high transition-colors">
                                    <span class="material-symbols-outlined text-base">edit</span> Edit
                                </a>
                                @if($item->master_products_count == 0)
                                <form method="POST" action="{{ route('admin.satuan.destroy', $item) }}"
                                      onsubmit="return confirm('Hapus satuan {{ addslashes($item->nama) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-error-container text-on-error-container text-xs font-bold hover:opacity-80 transition-opacity">
                                        <span class="material-symbols-outlined text-base">delete</span> Hapus
                                    </button>
                                </form>
                                @else
                                <span class="text-xs text-on-surface-variant italic">Tidak bisa dihapus</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-4xl block mb-2">straighten</span>
                            Belum ada satuan. <a href="{{ route('admin.satuan.create') }}" class="text-primary font-bold">Tambah sekarang</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($satuanList->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/10">
                {{ $satuanList->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
