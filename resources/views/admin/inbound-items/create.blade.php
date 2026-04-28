@extends('layouts.admin')

@section('title', 'Input Barang Masuk')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.inbound-items.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Input Barang Masuk</h1>
            <p class="text-on-surface-variant mt-0.5">Tambahkan data inbound dari distributor.</p>
        </div>
    </div>

        <form action="{{ route('admin.inbound-items.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="distributor_id" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Distributor <span class="text-error">*</span>
                </label>
                <select id="distributor_id" name="distributor_id" required
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('distributor_id') border-error @enderror">
                    <option value="">-- Pilih Distributor --</option>
                    @foreach($distributors as $distributor)
                        <option value="{{ $distributor->id }}" {{ old('distributor_id', request('distributor_id')) == $distributor->id ? 'selected' : '' }}>
                            {{ $distributor->name }}
                        </option>
                    @endforeach
                </select>
                @error('distributor_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="product_name" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Nama Produk <span class="text-error">*</span>
                </label>
                <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" required
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('product_name') border-error @enderror"
                       placeholder="Contoh: Susu UHT 1L"/>
                @error('product_name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="ukuran_produk" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Ukuran Produk <span class="text-error">*</span>
                </label>
                <input type="text" id="ukuran_produk" name="ukuran_produk" value="{{ old('ukuran_produk') }}" required
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('ukuran_produk') border-error @enderror"
                       placeholder="Contoh: 250ml, 1kg, 500gr"/>
                @error('ukuran_produk')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Kategori <span class="text-error">*</span>
                </label>
                <select id="category_id" name="category_id" required
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('category_id') border-error @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="product_photo" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Foto Produk
                </label>
                <input type="file" id="product_photo" name="product_photo" accept="image/*"
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary-fixed file:px-3 file:py-2 file:text-xs file:font-bold file:text-primary hover:file:bg-primary hover:file:text-on-primary @error('product_photo') border-error @enderror"/>
                <p class="text-[11px] text-on-surface-variant mt-1">Opsional. Format gambar umum, maksimal 2MB.</p>
                @error('product_photo')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="purchase_price" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Harga Beli (Rp) <span class="text-error">*</span>
                </label>
                <input type="number" step="0.01" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" min="0" required
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('purchase_price') border-error @enderror"
                       placeholder="Contoh: 5000"/>
                @error('purchase_price')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="selling_price" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Harga Jual (Rp) <span class="text-error">*</span>
                </label>
                <input type="number" step="0.01" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" min="0" required
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('selling_price') border-error @enderror"
                       placeholder="Contoh: 7000"/>
                @error('selling_price')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="quantity_inbound" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Jumlah Barang Masuk <span class="text-error">*</span>
                </label>
                <input type="number" id="quantity_inbound" name="quantity_inbound" value="{{ old('quantity_inbound') }}" min="1" required
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('quantity_inbound') border-error @enderror"/>
                @error('quantity_inbound')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="inbound_date" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Tanggal Masuk <span class="text-error">*</span>
                </label>
                <input type="date" id="inbound_date" name="inbound_date" value="{{ old('inbound_date', now()->toDateString()) }}" required
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('inbound_date') border-error @enderror"/>
                @error('inbound_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="expired_date" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Expired Date <span class="text-error">*</span>
                </label>
                <input type="date" id="expired_date" name="expired_date" value="{{ old('expired_date') }}" required
                      class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('expired_date') border-error @enderror"/>
                @error('expired_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label for="note" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Catatan</label>
                <textarea id="note" name="note" rows="2"
                          class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('note') border-error @enderror"
                          placeholder="Opsional">{{ old('note') }}</textarea>
                @error('note')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Barang Masuk
            </button>
            <a href="{{ route('admin.inbound-items.index') }}"
               class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const purchasePriceInput = document.getElementById('purchase_price');
    const sellingPriceInput = document.getElementById('selling_price');

    if (!purchasePriceInput || !sellingPriceInput) {
        return;
    }

    const syncSellingMin = () => {
        const purchaseValue = parseFloat(purchasePriceInput.value);
        const minValue = Number.isFinite(purchaseValue) ? purchaseValue : 0;

        sellingPriceInput.min = String(minValue);

        const sellingValue = parseFloat(sellingPriceInput.value);
        if (Number.isFinite(sellingValue) && sellingValue < minValue) {
            sellingPriceInput.setCustomValidity('Harga jual tidak boleh lebih rendah dari harga beli.');
        } else {
            sellingPriceInput.setCustomValidity('');
        }
    };

    purchasePriceInput.addEventListener('input', syncSellingMin);
    sellingPriceInput.addEventListener('input', syncSellingMin);

    syncSellingMin();
});
</script>
@endpush
@endsection
