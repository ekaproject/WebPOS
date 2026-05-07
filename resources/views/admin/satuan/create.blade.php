@extends('layouts.admin')

@section('title', 'Tambah Satuan')

@section('content')
<div class="max-w-lg mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.satuan.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Tambah Satuan</h1>
            <p class="text-on-surface-variant mt-0.5">Tambahkan satuan baru ke daftar master satuan.</p>
        </div>
    </div>

    <form action="{{ route('admin.satuan.store') }}" method="POST"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf

        <div>
            <label for="nama" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                Nama Satuan <span class="text-error">*</span>
            </label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                   class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('nama') border-error @enderror"
                   placeholder="Contoh: Kilogram, Liter, Botol"/>
            @error('nama')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="singkatan" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                Singkatan / Kode <span class="text-error">*</span>
            </label>
            <input type="text" id="singkatan" name="singkatan" value="{{ old('singkatan') }}" required
                   class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('singkatan') border-error @enderror"
                   placeholder="Contoh: kg, L, btl, pcs"/>
            <p class="text-[11px] text-on-surface-variant mt-1">Harus unik. Akan dikonversi ke huruf kecil.</p>
            @error('singkatan')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-2 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Satuan
            </button>
            <a href="{{ route('admin.satuan.index') }}"
               class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
