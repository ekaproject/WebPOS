@extends('layouts.admin')

@section('title', 'Edit Master Produk')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.master-products.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Edit Master Produk</h1>
            <p class="text-on-surface-variant mt-0.5">{{ $masterProduct->name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.master-products.update', $masterProduct) }}" method="POST" enctype="multipart/form-data"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="name" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Nama Produk <span class="text-error">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $masterProduct->name) }}" required
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('name') border-error @enderror"/>
                @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Kategori <span class="text-error">*</span>
                </label>
                <select id="category_id" name="category_id" required
                        class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('category_id') border-error @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $masterProduct->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="ukuran" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Ukuran Produk
                </label>
                <input type="text" id="ukuran" name="ukuran" value="{{ old('ukuran', $masterProduct->ukuran) }}"
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('ukuran') border-error @enderror"
                       placeholder="Contoh: 250ml, 1kg, 500gr"/>
                <p class="text-[11px] text-on-surface-variant mt-1">Opsional. Kombinasi Nama+Ukuran+Satuan harus unik.</p>
                @error('ukuran')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

                <div>
                    <label for="barcode" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                        Barcode
                    </label>
                    <input type="text" id="barcode" name="barcode" value="{{ old('barcode', $masterProduct->barcode) }}"
                           class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('barcode') border-error @enderror"
                           placeholder="Opsional, isi jika ada barcode"/>
                    @error('barcode')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror

                    <label for="unit" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5 mt-4">
                        Satuan Dasar Jual <span class="text-error">*</span>
                    </label>
                    <input type="text" id="unit" name="unit" value="{{ old('unit', $masterProduct->unit ?? 'pcs') }}" required
                           class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('unit') border-error @enderror"
                           placeholder="Contoh: pcs, botol, sachet, kg"/>
                    <p class="text-[11px] text-on-surface-variant mt-1">Satuan dasar jual yang tidak berubah sepanjang sistem berjalan.</p>
                    @error('unit')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror

                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5 mt-4">Harga Jual</label>
                    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $masterProduct->price) }}" min="0"
                           class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('price') border-error @enderror"/>
                    @error('price')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Deskripsi
                </label>
                <textarea id="description" name="description" rows="2"
                          class="w-full min-h-[80px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('description') border-error @enderror">{{ old('description', $masterProduct->description) }}</textarea>
                @error('description')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Foto Produk
                </label>
                @if($masterProduct->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $masterProduct->image) }}" alt="{{ $masterProduct->name }}"
                             class="w-20 h-20 rounded-xl object-cover border border-outline-variant/20">
                        <p class="text-[11px] text-on-surface-variant mt-1">Foto saat ini</p>
                    </div>
                @endif
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary-fixed file:px-3 file:py-2 file:text-xs file:font-bold file:text-primary hover:file:bg-primary hover:file:text-on-primary @error('image') border-error @enderror"/>
                <p class="text-[11px] text-on-surface-variant mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
                @error('image')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-4">
                <input type="checkbox" id="is_active" name="is_active" value="1"
                       class="w-4 h-4 rounded text-primary focus:ring-primary"
                       {{ old('is_active', $masterProduct->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                <label for="is_active" class="text-sm font-medium text-on-surface">Produk aktif</label>
            </div>
        </div>

        <div class="flex gap-3 pt-2 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.master-products.index') }}"
               class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
