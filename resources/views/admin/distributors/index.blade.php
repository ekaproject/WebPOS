@extends('layouts.admin')

@section('title', 'Manajemen Distributor')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Manajemen Distributor</h1>
            <p class="text-on-surface-variant mt-1">Kelola data distributor toko Anda</p>
        </div>
        <a href="{{ route('admin.distributors.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
           style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">add</span>
            Tambah Distributor
        </a>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-secondary-container text-on-secondary-container px-5 py-3 rounded-xl flex items-center gap-2 font-medium text-sm">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    {{-- Search --}}
    <form method="GET" class="flex gap-3">
        <div class="relative flex-1 max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-xl">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                   placeholder="Cari nama, kode, atau kontak..."/>
        </div>
        <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold">Cari</button>
        @if(request('search'))
            <a href="{{ route('admin.distributors.index') }}" class="px-5 py-2.5 rounded-xl bg-surface-container text-on-surface-variant text-sm font-bold">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Distributor</th>
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Kontak</th>
                        <th class="px-6 py-3">Telepon</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($distributors as $distributor)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center">
                                    <span class="material-symbols-outlined text-primary text-xl">local_shipping</span>
                                </div>
                                <div>
                                    <p class="font-bold">{{ $distributor->name }}</p>
                                    @if($distributor->email)
                                        <p class="text-xs text-on-surface-variant">{{ $distributor->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-on-surface-variant">{{ $distributor->code }}</td>
                        <td class="px-6 py-4">{{ $distributor->contact_person ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $distributor->phone ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold">{{ $distributor->products_count }}</td>
                        <td class="px-6 py-4">
                            @if($distributor->is_active)
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
                                <a href="{{ route('admin.distributors.show', $distributor) }}"
                                   class="p-2 rounded-xl bg-surface-container-high hover:bg-surface-container-highest text-on-surface-variant transition-colors"
                                   title="Detail">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </a>
                                <a href="{{ route('admin.distributors.edit', $distributor) }}"
                                   class="p-2 rounded-xl bg-primary-fixed hover:bg-primary hover:text-on-primary text-primary transition-colors"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.distributors.destroy', $distributor) }}"
                                      onsubmit="return confirm('Hapus distributor {{ $distributor->name }}?')">
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
                        <td colspan="7" class="px-6 py-16 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl block mb-2">local_shipping</span>
                            Belum ada distributor. <a href="{{ route('admin.distributors.create') }}" class="text-primary font-bold">Tambah sekarang →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($distributors->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant/10">
            {{ $distributors->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
