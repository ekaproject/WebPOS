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
        <a href="{{ route('admin.inbound-items.create', ['distributor_id' => $distributor->id]) }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-primary-fixed text-primary hover:bg-primary hover:text-on-primary transition-colors">
            <span class="material-symbols-outlined text-lg">box_add</span>
            Input Barang Masuk
        </a>
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
</div>
@endsection
