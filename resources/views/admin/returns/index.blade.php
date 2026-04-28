@extends('layouts.admin')

@section('title', 'Manajemen Return Barang')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Manajemen Return Barang</h1>
            <p class="text-on-surface-variant mt-1">Daftar barang rusak hasil QC yang menunggu atau sudah selesai retur ke distributor.</p>
        </div>
        <a href="{{ route('admin.inbound-items.index') }}" class="px-5 py-2.5 rounded-xl bg-primary text-on-primary text-sm font-bold inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-base">inventory_2</span>
            Lihat Barang Masuk
        </a>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-4">
        <form action="{{ route('admin.returns.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Cari Return</label>
                  <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk atau distributor"
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Status</label>
                <select name="status" class="h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl font-bold text-sm">Filter</button>
            <a href="{{ route('admin.returns.index') }}" class="px-5 py-2 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Reset</a>
        </form>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">ID Return</th>
                        <th class="px-6 py-3">Distributor</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3">Qty Rusak</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @forelse($returns as $return)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-primary font-bold">#{{ $return->id }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $return->distributor->name }}</td>
                        <td class="px-6 py-4">{{ $return->product_name }}</td>
                        <td class="px-6 py-4 font-bold text-error">{{ $return->qty }}</td>
                        <td class="px-6 py-4">
                            @if($return->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Completed</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $return->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.returns.show', $return) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-primary bg-primary-fixed hover:bg-primary hover:text-on-primary transition-colors">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl block mb-2">undo</span>
                            Tidak ada data return dengan status ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($returns->hasPages())
        <div class="px-6 py-4 border-t border-outline-variant/10">
            {{ $returns->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
