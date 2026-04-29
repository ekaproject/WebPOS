@extends('layouts.admin')

@section('title', 'Return Distributor')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Return Barang</h1>
            <p class="text-on-surface-variant mt-1">Daftar return untuk distributor {{ $distributor->name }}.</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-4">
        <form action="{{ route('distributor.returns.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Cari Return</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama produk"
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-1 uppercase tracking-wider">Status</label>
                <select name="status" class="h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <button type="submit" class="px-5 py-2 bg-primary text-on-primary rounded-xl font-bold text-sm">Filter</button>
            <a href="{{ route('distributor.returns.index') }}" class="px-5 py-2 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Reset</a>
        </form>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container-low text-left text-xs font-bold uppercase tracking-wider text-on-surface-variant">
                        <th class="px-6 py-3">ID Return</th>
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
                        <td class="px-6 py-4 font-semibold">{{ $return->product_name }}</td>
                        <td class="px-6 py-4 font-bold text-error">{{ $return->qty }}</td>
                        <td class="px-6 py-4">
                            @if($return->status === 'completed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-secondary-container text-on-secondary-container">Selesai</span>
                            @elseif($return->status === 'confirmed')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-fixed text-primary">Dikonfirmasi</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-tertiary-fixed text-tertiary">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-on-surface-variant">{{ $return->created_at->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($return->status === 'pending')
                                <form action="{{ route('distributor.returns.confirm', $return->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-primary bg-primary-fixed hover:bg-primary hover:text-on-primary transition-colors">
                                        Konfirmasi
                                    </button>
                                </form>
                            @elseif($return->status === 'confirmed')
                                <form action="{{ route('distributor.returns.complete', $return->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-secondary bg-secondary-container hover:bg-secondary hover:text-on-secondary transition-colors">
                                        Selesaikan
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-on-surface-variant">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-5xl block mb-2">undo</span>
                            Tidak ada data return.
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
