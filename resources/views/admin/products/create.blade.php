@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Tambah Produk</h1>
            <p class="text-on-surface-variant mt-0.5">Isi detail produk baru di bawah ini</p>
        </div>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="name">Nama Produk<span class="text-error">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary @error('name') border-error @enderror"
                       placeholder="Cth: Salmon Fillet Premium 200g"/>
                @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="sku">SKU<span class="text-error">*</span></label>
                  <input type="text" id="sku" name="sku" value="{{ old('sku') }}" readonly
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm font-mono focus:ring-2 focus:ring-primary @error('sku') border-error @enderror"
                      placeholder="Pilih kategori untuk generate SKU"/>
                  <p class="text-xs text-on-surface-variant mt-1">SKU otomatis berdasarkan kategori, contoh: MIN-0001</p>
                @error('sku')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="category_id">Kategori<span class="text-error">*</span></label>
                <select id="category_id" name="category_id" required
                        class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary @error('category_id') border-error @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="purchase_price">Harga Beli (Rp)<span class="text-error">*</span></label>
                <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" min="0" required
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary @error('purchase_price') border-error @enderror"
                       placeholder="Cth: 75000"/>
                @error('purchase_price')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="price">Harga Jual (Rp)<span class="text-error">*</span></label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" required
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary @error('price') border-error @enderror"
                       placeholder="Cth: 85000"/>
                @error('price')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="unit">Satuan<span class="text-error">*</span></label>
                <select id="unit" name="unit" required
                        class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary @error('unit') border-error @enderror">
                    <option value="">-- Pilih Satuan --</option>
                    <option value="pcs" {{ old('unit') === 'pcs' ? 'selected' : '' }}>pcs</option>
                    <option value="kg" {{ old('unit') === 'kg' ? 'selected' : '' }}>kg</option>
                    <option value="liter" {{ old('unit') === 'liter' ? 'selected' : '' }}>liter</option>
                </select>
                @error('unit')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="stock">Stok Awal<span class="text-error">*</span></label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary @error('stock') border-error @enderror"/>
                @error('stock')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="min_stock">Stok Minimum</label>
                <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', 5) }}" min="0"
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                <p class="text-xs text-on-surface-variant mt-1">Notifikasi stok kritis di bawah nilai ini</p>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="expires_at">Tanggal Kadaluarsa</label>
                <input type="date" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                       class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-xl border-outline-variant/40 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"
                          placeholder="Deskripsi singkat produk...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="image">Foto Produk</label>
                <input type="file" id="image" name="image" accept="image/*"
                       class="w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-primary-fixed file:text-primary hover:file:bg-primary hover:file:text-on-primary"/>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-primary rounded border-outline-variant focus:ring-primary"/>
                <label for="is_active" class="text-sm font-semibold text-on-surface">Produk Aktif (tampilkan di kasir)</label>
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Produk
            </button>
            <a href="{{ route('admin.products.index') }}" class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('category_id');
    const skuInput = document.getElementById('sku');

    async function refreshSku() {
        const categoryId = categorySelect.value;
        if (!categoryId) {
            skuInput.value = '';
            return;
        }

        try {
            const response = await fetch(`{{ url('/admin/categories') }}/${categoryId}/next-sku`);
            if (!response.ok) {
                return;
            }

            const data = await response.json();
            skuInput.value = data.sku || '';
        } catch (error) {
            console.error('Gagal generate SKU:', error);
        }
    }

    categorySelect.addEventListener('change', refreshSku);

    if (categorySelect.value && !skuInput.value) {
        refreshSku();
    }
});
</script>
@endpush
@endsection
