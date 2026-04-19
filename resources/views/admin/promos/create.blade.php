@extends('layouts.admin')

@section('title', 'Tambah Promo')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.promos.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Tambah Promo</h1>
            <p class="text-on-surface-variant mt-0.5">Buat promo harian atau voucher belanja tanpa banner</p>
        </div>
    </div>

    <form action="{{ route('admin.promos.store') }}" method="POST"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="title">Judul Promo <span class="text-error">*</span></label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required
                       class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('title') border-error @enderror"
                       placeholder="Cth: Diskon Belanja Akhir Pekan"/>
                @error('title')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <input type="hidden" name="type" value="fixed">

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Tipe Promo</label>
                <div class="px-4 py-2.5 rounded-xl bg-surface-container text-sm font-semibold text-on-surface border border-outline-variant/20">
                    Potongan Nominal (Rp)
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="discount_value">Nilai Diskon <span class="text-error">*</span></label>
                <input type="number" step="0.01" id="discount_value" name="discount_value" value="{{ old('discount_value') }}" required
                       class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('discount_value') border-error @enderror"
                       placeholder="Cth: 15000"/>
                <p class="text-xs text-on-surface-variant mt-1">Masukkan nominal potongan dalam Rupiah.</p>
                @error('discount_value')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="min_purchase">Minimum Belanja</label>
                <input type="number" step="0.01" id="min_purchase" name="min_purchase" value="{{ old('min_purchase') }}"
                       class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('min_purchase') border-error @enderror"
                       placeholder="Cth: 100000"/>
                <p class="text-xs text-on-surface-variant mt-1">Kosongkan jika promo harian tanpa syarat minimum</p>
                @error('min_purchase')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="voucher_quota">Kuota Voucher</label>
                <input type="number" id="voucher_quota" name="voucher_quota" value="{{ old('voucher_quota', 10) }}" min="1"
                       class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('voucher_quota') border-error @enderror"
                       placeholder="Default: 10"/>
                <p class="text-xs text-on-surface-variant mt-1">Dipakai saat Minimum Belanja diisi. Kode voucher dibuat otomatis.</p>
                @error('voucher_quota')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="category_id">Kategori (Opsional)</label>
                <select id="category_id" name="category_id"
                        class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('category_id') border-error @enderror">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="product_id">Produk Promo <span class="text-error">*</span></label>
                <select id="product_id" name="product_id" required
                        class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('product_id') border-error @enderror">
                    <option value="">Pilih Produk Promo</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->category?->name ?? 'Tanpa Kategori' }})
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-on-surface-variant mt-1">Pilih produk promo untuk validasi anti-rugi berdasarkan harga beli produk.</p>
                @error('product_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2 bg-surface-container rounded-xl border border-outline-variant/20 p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-3">Informasi Harga Produk Terpilih</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-on-surface-variant">Harga Beli</p>
                        <p class="font-bold text-on-surface" id="promo-product-purchase-price">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant">Harga Jual</p>
                        <p class="font-bold text-primary" id="promo-product-selling-price">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant">Maks. Potongan Aman</p>
                        <p class="font-bold text-secondary" id="promo-product-max-discount">-</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="start_date">Tanggal Mulai <span class="text-error">*</span></label>
                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}" required
                       class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('start_date') border-error @enderror"/>
                @error('start_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="end_date">Tanggal Berakhir <span class="text-error">*</span></label>
                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', now()->addDays(7)->format('Y-m-d')) }}" required
                       class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('end_date') border-error @enderror"/>
                @error('end_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="description">Deskripsi Promo</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('description') border-error @enderror"
                          placeholder="Cth: Min belanja 100 ribu dapat diskon 10% untuk kategori makanan.">{{ old('description') }}</textarea>
                @error('description')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2 flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                       class="rounded border-outline-variant text-primary focus:ring-primary"/>
                <label for="is_active" class="text-sm font-medium">Aktifkan promo</label>
            </div>
        </div>

        <div class="flex gap-3 pt-2 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Promo
            </button>
            <a href="{{ route('admin.promos.index') }}"
               class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('product_id');
    const purchasePriceEl = document.getElementById('promo-product-purchase-price');
    const sellingPriceEl = document.getElementById('promo-product-selling-price');
    const maxDiscountEl = document.getElementById('promo-product-max-discount');

    const formatRupiah = (value) => {
        if (value === null || value === undefined || value === '') return '-';
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    };

    const productMap = {
        @foreach($products as $product)
        {{ $product->id }}: {
            purchase_price: {{ (float) ($product->purchase_price ?? 0) }},
            selling_price: {{ (float) $product->price }}
        },
        @endforeach
    };

    const renderProductPriceInfo = () => {
        const selectedId = productSelect.value;
        const selectedProduct = productMap[selectedId];

        if (!selectedProduct) {
            purchasePriceEl.textContent = '-';
            sellingPriceEl.textContent = '-';
            maxDiscountEl.textContent = '-';
            return;
        }

        const purchasePrice = Number(selectedProduct.purchase_price || 0);
        const sellingPrice = Number(selectedProduct.selling_price || 0);
        const maxDiscount = Math.max(sellingPrice - purchasePrice, 0);

        purchasePriceEl.textContent = formatRupiah(purchasePrice);
        sellingPriceEl.textContent = formatRupiah(sellingPrice);
        maxDiscountEl.textContent = formatRupiah(maxDiscount);
    };

    productSelect.addEventListener('change', renderProductPriceInfo);
    renderProductPriceInfo();
});
</script>
@endpush
