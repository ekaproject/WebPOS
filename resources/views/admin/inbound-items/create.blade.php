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
                <label for="master_product_search" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Nama Produk <span class="text-error">*</span>
                </label>
                <input type="hidden" id="master_product_id" name="master_product_id" value="{{ old('master_product_id') }}"/>
                <input type="hidden" id="category_id" name="category_id" value="{{ old('category_id') }}"/>

                <div class="relative">
                    <input type="text" id="master_product_search" autocomplete="off"
                           placeholder="Ketik untuk mencari produk..."
                           class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('master_product_id') border-error @enderror"
                           value="{{ old('product_name') }}"/>
                    <div id="master_product_dropdown"
                         class="absolute z-50 w-full mt-1 bg-white border border-outline-variant/30 rounded-xl shadow-lg max-h-56 overflow-y-auto hidden">
                    </div>
                </div>
                @error('master_product_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-[11px] text-on-surface-variant mt-1">
                    Produk tidak ada? <a href="{{ route('admin.master-products.create') }}" target="_blank" class="text-primary underline">Tambah master produk</a> terlebih dahulu.
                </p>
            </div>

            <div>
                <label for="ukuran_produk" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Ukuran Produk <span class="text-error">*</span>
                </label>
                <input type="text" id="ukuran_produk" name="ukuran_produk" value="{{ old('ukuran_produk') }}" readonly
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-surface-container text-sm @error('ukuran_produk') border-error @enderror"
                    placeholder="Terisi otomatis dari master produk"/>
            </div>

            <div>
                <label for="satuan_produk" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Satuan
                </label>
                <input type="text" id="satuan_produk" value="" readonly
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-surface-container text-sm"
                    placeholder="Terisi otomatis"/>
            </div>

            <div>
                <label for="kategori_produk_display" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Kategori
                </label>
                <input type="text" id="kategori_produk_display" value="" readonly
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-surface-container text-sm"
                    placeholder="Terisi otomatis"/>
                @error('category_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="harga_master_display" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Harga Master
                </label>
                <input type="text" id="harga_master_display" value="" readonly
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-surface-container text-sm"
                    placeholder="Terisi otomatis"/>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Foto Produk (Master)
                </label>
                <div class="h-24 rounded-xl border border-outline-variant/30 bg-surface-container overflow-hidden flex items-center justify-center">
                    <img id="master_product_photo_preview" src="" alt="Preview foto produk" class="hidden h-full w-full object-contain bg-white">
                    <span id="master_product_photo_placeholder" class="text-xs text-on-surface-variant">Belum ada foto produk</span>
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="product_photo" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Foto Produk Barang Masuk (Opsional)
                </label>
                <input type="file" id="product_photo" name="product_photo" accept="image/*"
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary-fixed file:px-3 file:py-2 file:text-xs file:font-bold file:text-primary hover:file:bg-primary hover:file:text-on-primary @error('product_photo') border-error @enderror"/>
                <p class="text-[11px] text-on-surface-variant mt-1">Opsional. Jika kosong, tetap menggunakan foto dari master produk sebagai referensi visual.</p>
                @error('product_photo')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

                <div id="inbound_product_photo_preview_container" class="md:col-span-2 hidden">
                    <p class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">Preview Foto Barang Masuk</p>
                    <div class="h-32 rounded-xl border border-outline-variant/30 bg-surface-container overflow-hidden flex items-center justify-center">
                        <img id="inbound_product_photo_preview" src="" alt="Preview foto barang masuk" class="h-full w-full object-contain bg-white">
                    </div>
                </div>

            <div>
                <label for="kemasan_beli" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Kemasan Beli <span class="text-error">*</span>
                </label>
                <select id="kemasan_beli" name="kemasan_beli" required
                        class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('kemasan_beli') border-error @enderror">
                    <option value="">-- Pilih Kemasan Beli --</option>
                    @foreach($satuans as $satuan)
                        <option value="{{ $satuan->nama }}" {{ old('kemasan_beli') == $satuan->nama ? 'selected' : '' }}>
                            {{ $satuan->label }}
                        </option>
                    @endforeach
                </select>
                @error('kemasan_beli')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="isi_per_kemasan" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Isi per Kemasan <span class="text-error">*</span>
                </label>
                <input type="number" id="isi_per_kemasan" name="isi_per_kemasan" value="{{ old('isi_per_kemasan') }}" min="1" required
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('isi_per_kemasan') border-error @enderror"
                       placeholder="Contoh: 24"/>
                @error('isi_per_kemasan')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="jumlah_kemasan" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Jumlah Kemasan <span class="text-error">*</span>
                </label>
                <input type="number" id="jumlah_kemasan" name="jumlah_kemasan" value="{{ old('jumlah_kemasan') }}" min="1" required
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('jumlah_kemasan') border-error @enderror"
                       placeholder="Contoh: 2"/>
                @error('jumlah_kemasan')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="quantity_inbound" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">
                    Quantity Inbound (otomatis)
                </label>
                <input type="number" id="quantity_inbound" name="quantity_inbound" value="{{ old('quantity_inbound') }}" min="1" readonly
                       class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-surface-container text-sm focus:ring-2 focus:ring-primary @error('quantity_inbound') border-error @enderror"
                       placeholder="Terhitung otomatis"/>
                <p class="text-[11px] text-on-surface-variant mt-1">Qty final = jumlah kemasan x isi per kemasan.</p>
                @error('quantity_inbound')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
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
                      placeholder="Terisi dari master produk"/>
                  <p class="text-[11px] text-on-surface-variant mt-1">Diambil otomatis dari master produk, boleh disesuaikan bila perlu.</p>
                @error('selling_price')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
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
    const searchEndpoint = @json(route('admin.inbound-items.master-products.search'));
    const detailTemplate = @json(route('admin.inbound-items.master-products.detail', ['masterProduct' => '__ID__']));

    const searchInput = document.getElementById('master_product_search');
    const hiddenProductId = document.getElementById('master_product_id');
    const hiddenCategoryId = document.getElementById('category_id');
    const dropdown = document.getElementById('master_product_dropdown');

    const ukuranInput = document.getElementById('ukuran_produk');
    const satuanInput = document.getElementById('satuan_produk');
    const kategoriDisplay = document.getElementById('kategori_produk_display');
    const hargaMasterDisplay = document.getElementById('harga_master_display');
    const previewImage = document.getElementById('master_product_photo_preview');
    const previewPlaceholder = document.getElementById('master_product_photo_placeholder');

    const isiInput = document.getElementById('isi_per_kemasan');
    const jumlahInput = document.getElementById('jumlah_kemasan');
    const quantityInput = document.getElementById('quantity_inbound');

    let searchTimer = null;

    function formatRupiah(value) {
        if (value === null || value === undefined || value === '') {
            return '';
        }

        const n = Number(value);
        if (!Number.isFinite(n)) {
            return '';
        }

        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function syncQuantity() {
        if (!quantityInput) {
            return;
        }

        const isi = parseInt(isiInput?.value || '', 10);
        const jumlah = parseInt(jumlahInput?.value || '', 10);

        if (Number.isFinite(isi) && Number.isFinite(jumlah)) {
            quantityInput.value = String(isi * jumlah);
            return;
        }

        quantityInput.value = '';
    }

    function clearProductDetails() {
        if (hiddenProductId) hiddenProductId.value = '';
        if (hiddenCategoryId) hiddenCategoryId.value = '';
        if (ukuranInput) ukuranInput.value = '';
        if (satuanInput) satuanInput.value = '';
        if (kategoriDisplay) kategoriDisplay.value = '';
        if (hargaMasterDisplay) hargaMasterDisplay.value = '';

        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }

        if (previewPlaceholder) {
            previewPlaceholder.textContent = 'Belum ada foto produk';
            previewPlaceholder.classList.remove('hidden');
        }
    }

    function applyProductDetail(product) {
        if (!product) {
            clearProductDetails();
            return;
        }

        if (hiddenProductId) hiddenProductId.value = product.id || '';
        if (hiddenCategoryId) hiddenCategoryId.value = product.category_id || '';
        if (searchInput) searchInput.value = product.name || '';
        if (ukuranInput) ukuranInput.value = product.ukuran || '';
        if (satuanInput) satuanInput.value = product.satuan || '';
        if (kategoriDisplay) kategoriDisplay.value = product.category_name || '';
        if (hargaMasterDisplay) hargaMasterDisplay.value = formatRupiah(product.price) || '-';

        if (sellingPriceInput && (sellingPriceInput.value === '' || sellingPriceInput.dataset.autofill !== 'manual')) {
            if (product.price !== null && product.price !== undefined) {
                sellingPriceInput.value = product.price;
                sellingPriceInput.dataset.autofill = 'master';
            }
        }

        if (previewImage && previewPlaceholder) {
            if (product.image_url) {
                previewImage.src = product.image_url;
                previewImage.classList.remove('hidden');
                previewPlaceholder.classList.add('hidden');
            } else {
                previewImage.src = '';
                previewImage.classList.add('hidden');
                previewPlaceholder.textContent = 'Produk ini tidak memiliki foto';
                previewPlaceholder.classList.remove('hidden');
            }
        }
    }

    async function fetchProductDetail(productId) {
        if (!productId) {
            clearProductDetails();
            return;
        }

        try {
            const url = detailTemplate.replace('__ID__', encodeURIComponent(String(productId)));
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil detail produk.');
            }

            const product = await response.json();
            applyProductDetail(product);
        } catch (error) {
            clearProductDetails();
        }
    }

    function renderDropdown(items) {
        if (!dropdown) {
            return;
        }

        dropdown.innerHTML = '';

        if (!items.length) {
            dropdown.innerHTML = '<div class="px-4 py-3 text-sm text-on-surface-variant">Produk tidak ditemukan</div>';
            dropdown.classList.remove('hidden');
            return;
        }

        items.forEach((item) => {
            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'w-full text-left px-4 py-3 text-sm hover:bg-surface-container-low transition-colors';
            option.textContent = item.name;
            option.addEventListener('click', function () {
                dropdown.classList.add('hidden');
                fetchProductDetail(item.id);
            });
            dropdown.appendChild(option);
        });

        dropdown.classList.remove('hidden');
    }

    async function searchProducts(keyword) {
        if (!dropdown) {
            return;
        }

        if (!keyword) {
            dropdown.classList.add('hidden');
            dropdown.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(searchEndpoint + '?q=' + encodeURIComponent(keyword), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Gagal mencari produk.');
            }

            const items = await response.json();
            renderDropdown(items);
        } catch (error) {
            dropdown.classList.add('hidden');
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            if (searchTimer) {
                clearTimeout(searchTimer);
            }

            hiddenProductId.value = '';
            hiddenCategoryId.value = '';
            ukuranInput.value = '';
            satuanInput.value = '';
            kategoriDisplay.value = '';
            hargaMasterDisplay.value = '';

            if (previewImage) {
                previewImage.src = '';
                previewImage.classList.add('hidden');
            }

            if (previewPlaceholder) {
                previewPlaceholder.textContent = 'Belum ada foto produk';
                previewPlaceholder.classList.remove('hidden');
            }

            searchTimer = setTimeout(() => {
                searchProducts(this.value.trim());
            }, 250);
        });
    }

    document.addEventListener('click', function (event) {
        if (!dropdown || !searchInput) {
            return;
        }

        if (!dropdown.contains(event.target) && !searchInput.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    if (hiddenProductId && hiddenProductId.value) {
        fetchProductDetail(hiddenProductId.value);
    }

    if (sellingPriceInput) {
        sellingPriceInput.addEventListener('input', function () {
            this.dataset.autofill = 'manual';
        });
    }

    if (isiInput) isiInput.addEventListener('input', syncQuantity);
    if (jumlahInput) jumlahInput.addEventListener('input', syncQuantity);

    syncQuantity();

        // Handle product_photo preview
        const productPhotoInput = document.getElementById('product_photo');
        const previewContainer = document.getElementById('inbound_product_photo_preview_container');
        const previewImageInbound = document.getElementById('inbound_product_photo_preview');

        if (productPhotoInput && previewContainer && previewImageInbound) {
            productPhotoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        previewImageInbound.src = event.target.result;
                        previewContainer.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImageInbound.src = '';
                    previewContainer.classList.add('hidden');
                }
            });
        }
});
</script>
@endpush
@endsection
