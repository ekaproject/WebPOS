@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.categories.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Edit Kategori</h1>
            <p class="text-on-surface-variant mt-0.5">Perbarui detail kategori <strong>{{ $category->name }}</strong></p>
        </div>
    </div>

    <form action="{{ route('admin.categories.update', $category) }}" method="POST"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="name">
                    Nama Kategori <span class="text-error">*</span>
                </label>
                  <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('name') border-error @enderror"
                       placeholder="Cth: Produk Segar"/>
                @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="icon">
                    Icon (Material Symbol) <span class="text-error">*</span>
                </label>
                <div class="flex gap-3 items-center">
                          <input type="text" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" required
                              class="flex-1 h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('icon') border-error @enderror"
                           placeholder="Cth: nutrition, restaurant, clean_hands"
                           oninput="document.getElementById('icon-preview').textContent = this.value"/>
                          <div class="w-11 h-11 rounded-xl bg-primary-fixed flex items-center justify-center flex-none">
                        <span id="icon-preview" class="material-symbols-outlined text-primary text-xl">{{ old('icon', $category->icon) }}</span>
                    </div>
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Gunakan nama icon dari <a href="https://fonts.google.com/icons" target="_blank" class="text-primary underline">Google Material Symbols</a></p>
                @error('icon')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="type">
                    Tipe <span class="text-error">*</span>
                </label>
                <select id="type" name="type" required
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('type') border-error @enderror">
                    <option value="fmcg"    {{ old('type', $category->type) === 'fmcg'    ? 'selected' : '' }}>FMCG (Barang Umum)</option>
                    <option value="fresh"   {{ old('type', $category->type) === 'fresh'   ? 'selected' : '' }}>Fresh (Produk Segar)</option>
                    <option value="fnb"     {{ old('type', $category->type) === 'fnb'     ? 'selected' : '' }}>F&B (Makanan & Minuman)</option>
                    <option value="digital" {{ old('type', $category->type) === 'digital' ? 'selected' : '' }}>Digital</option>
                </select>
                @error('type')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="description">
                    Deskripsi Singkat
                </label>
                  <input type="text" id="description" name="description" value="{{ old('description', $category->description) }}"
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                       placeholder="Cth: Buah, Sayur, Daging"/>
                <p class="text-xs text-on-surface-variant mt-1">Tampil sebagai subtitle di kartu kategori</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Slug (otomatis)</label>
                <div class="px-4 py-2.5 rounded-xl bg-surface-container text-sm font-mono text-on-surface-variant border border-outline-variant/20">
                    {{ $category->slug }}
                </div>
                <p class="text-xs text-on-surface-variant mt-1">Slug akan diperbarui otomatis jika nama diubah</p>
            </div>

            <div class="md:col-span-2 flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                       class="rounded border-outline-variant text-primary focus:ring-primary"/>
                <label for="is_active" class="text-sm font-medium">Aktif (ditampilkan di halaman publik)</label>
            </div>
        </div>

        <div class="flex gap-3 pt-2 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.categories.index') }}"
               class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
