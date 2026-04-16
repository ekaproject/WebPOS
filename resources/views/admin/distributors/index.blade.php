@extends('layouts.admin')

@section('title', 'Manajemen Distributor')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Manajemen Distributor</h1>
            <p class="text-on-surface-variant mt-1">Kelola data distributor toko Anda</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 block mb-3">local_shipping</span>
        <p class="text-on-surface-variant font-medium">Halaman distributor siap digunakan.</p>
        <p class="text-xs text-on-surface-variant mt-1">Tambahkan fitur CRUD sesuai kebutuhan.</p>
    </div>
</div>
@endsection
