@extends('layouts.admin')

@section('title', 'Manajemen Promo')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Manajemen Promo</h1>
            <p class="text-on-surface-variant mt-1">Kelola promo harian dan voucher belanja</p>
        </div>
        <a href="{{ route('admin.promos.create') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm text-on-primary"
           style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
            <span class="material-symbols-outlined text-xl">add</span>
            Tambah Promo
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-4">
        <form action="{{ route('admin.promos.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Cari Promo</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Judul atau deskripsi promo..."
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Tipe</label>
                <select name="type" class="rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary">
                    <option value="">Semua</option>
                    <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Diskon %</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Potongan Nominal</option>
                    <option value="free_item" {{ request('type') === 'free_item' ? 'selected' : '' }}>Gratis Item</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Status</label>
                <select name="status" class="rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary">
                    <option value="">Semua</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl font-bold text-sm">Filter</button>
            <a href="{{ route('admin.promos.index') }}" class="px-5 py-2 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Reset</a>
        </form>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">Promo</th>
                        <th class="px-6 py-3">Produk Promo</th>
                        <th class="px-6 py-3">Tipe</th>
                        <th class="px-6 py-3">Benefit</th>
                        <th class="px-6 py-3">Min. Belanja</th>
                        <th class="px-6 py-3">Kode Voucher</th>
                        <th class="px-6 py-3">Sisa Kuota</th>
                        <th class="px-6 py-3">Periode</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($promos as $promo)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold">{{ $promo->title }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $promo->category?->name ?? 'Semua Kategori' }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $promo->product?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-primary-fixed text-primary">{{ strtoupper($promo->type) }}</span>
                            </td>
                            <td class="px-6 py-4 font-bold text-primary">{{ $promo->discount_label }}</td>
                            <td class="px-6 py-4">
                                {{ $promo->min_purchase ? 'Rp '.number_format((float) $promo->min_purchase, 0, ',', '.') : '-' }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $promo->voucher_code ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if(is_null($promo->voucher_quota))
                                    -
                                @else
                                    <span class="font-bold {{ $promo->voucher_remaining > 0 ? 'text-secondary' : 'text-error' }}">{{ $promo->voucher_remaining }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-on-surface-variant">
                                {{ $promo->start_date->format('d M Y') }} - {{ $promo->end_date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($promo->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-surface-container text-on-surface-variant">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.promos.edit', $promo) }}"
                                       class="p-2 rounded-lg bg-primary-fixed text-primary hover:bg-primary hover:text-on-primary transition-colors"
                                       title="Edit">
                                        <span class="material-symbols-outlined text-base">edit</span>
                                    </a>
                                    <form action="{{ route('admin.promos.destroy', $promo) }}" method="POST"
                                          onsubmit="return confirm('Hapus promo {{ addslashes($promo->title) }}?')">
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
                            <td colspan="10" class="px-6 py-16 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-5xl block mb-2">local_offer</span>
                                Belum ada promo. <a href="{{ route('admin.promos.create') }}" class="text-primary font-bold">Tambah sekarang -></a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($promos->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant/10">
                {{ $promos->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
