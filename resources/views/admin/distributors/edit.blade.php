@extends('layouts.admin')

@section('title', 'Edit Distributor')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.distributors.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Edit Distributor</h1>
            <p class="text-on-surface-variant mt-0.5">Perbarui detail distributor <strong>{{ $distributor->name }}</strong></p>
        </div>
    </div>

    <form action="{{ route('admin.distributors.update', $distributor) }}" method="POST"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf
        @method('PUT')

        {{-- Info Distributor --}}
        <div>
            <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">business</span>
                Informasi Distributor
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="name">
                        Nama Distributor <span class="text-error">*</span>
                    </label>
                          <input type="text" id="name" name="name" value="{{ old('name', $distributor->name) }}" required
                              class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('name') border-error @enderror"
                           placeholder="Cth: PT Indofood Sukses Makmur"/>
                    @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="code">
                        Kode Distributor <span class="text-error">*</span>
                    </label>
                          <input type="text" id="code" name="code" value="{{ old('code', $distributor->code) }}" required
                              class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('code') border-error @enderror"
                           placeholder="Cth: DIST-001"/>
                    @error('code')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="contact_person">
                        Kontak Person
                    </label>
                          <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $distributor->contact_person) }}"
                              class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                           placeholder="Cth: Pak Budi"/>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="phone">
                        No. Telepon
                    </label>
                          <input type="text" id="phone" name="phone" value="{{ old('phone', $distributor->phone) }}"
                              class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                           placeholder="Cth: 08123456789"/>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="email">
                        Email
                    </label>
                          <input type="email" id="email" name="email" value="{{ old('email', $distributor->email) }}"
                              class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('email') border-error @enderror"
                           placeholder="Cth: distributor@email.com"/>
                    @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="address">
                        Alamat
                    </label>
                    <textarea id="address" name="address" rows="2"
                              class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                              placeholder="Cth: Jl. Sudirman No. 10, Jakarta Pusat">{{ old('address', $distributor->address) }}</textarea>
                </div>

                <div class="md:col-span-2 flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', $distributor->is_active) ? 'checked' : '' }}
                           class="rounded border-outline-variant text-primary focus:ring-primary"/>
                    <label for="is_active" class="text-sm font-medium">Aktif</label>
                </div>
            </div>
        </div>

        <div class="flex gap-3 pt-2 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.distributors.index') }}"
               class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
