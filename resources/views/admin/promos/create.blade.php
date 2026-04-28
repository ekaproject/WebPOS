@extends('layouts.admin')

@section('title', 'Tambah Promo')

@section('content')
@php
    $defaultStartDate = now()->format('Y-m-d');
    $defaultEndDate = now()->addDays(7)->format('Y-m-d');
    $oldProducts = old('products', [[
        'product_id' => '',
        'type' => 'fixed',
        'discount_value' => '',
        'start_date' => $defaultStartDate,
        'end_date' => $defaultEndDate,
    ]]);
@endphp

<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.promos.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Tambah Promo Multi Produk</h1>
            <p class="text-on-surface-variant mt-0.5">Pilih produk terlebih dulu, lalu isi detail promo per produk.</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="rounded-xl border border-outline-variant/20 bg-surface-container-low p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-primary mb-1">Step 1</p>
                <p class="font-semibold text-on-surface">Pilih produk yang akan dipromo.</p>
            </div>
            <div class="rounded-xl border border-outline-variant/20 bg-surface-container-low p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-primary mb-1">Step 2</p>
                <p class="font-semibold text-on-surface">Isi jenis promo, nilai diskon, dan periode.</p>
            </div>
            <div class="rounded-xl border border-outline-variant/20 bg-surface-container-low p-4">
                <p class="text-xs font-bold uppercase tracking-wider text-primary mb-1">Step 3</p>
                <p class="font-semibold text-on-surface">Gunakan tombol tambah produk untuk input produk berikutnya.</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.promos.store') }}" method="POST"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf

        @error('products')
            <div class="rounded-xl bg-error-container/40 border border-error/20 px-4 py-3 text-sm text-error">{{ $message }}</div>
        @enderror

        <div class="flex items-center gap-3">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                   class="rounded border-outline-variant text-primary focus:ring-primary"/>
            <label for="is_active" class="text-sm font-medium">Aktifkan promo setelah disimpan</label>
        </div>

        <div id="promo-products-container" class="space-y-4">
            @foreach($oldProducts as $index => $row)
                @include('admin.promos.partials.product-row', [
                    'index' => $index,
                    'row' => $row,
                    'products' => $products,
                ])
            @endforeach
        </div>

        <div class="pt-1">
            <button type="button" id="add-promo-product"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm text-primary bg-primary-fixed hover:bg-primary hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah Produk
            </button>
        </div>

        <template id="promo-product-template">
            @include('admin.promos.partials.product-row', [
                'index' => '__INDEX__',
                'row' => [
                    'product_id' => '',
                    'type' => 'fixed',
                    'discount_value' => '',
                    'start_date' => $defaultStartDate,
                    'end_date' => $defaultEndDate,
                ],
                'products' => $products,
            ])
        </template>

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
    const container = document.getElementById('promo-products-container');
    const addButton = document.getElementById('add-promo-product');
    const template = document.getElementById('promo-product-template');

    if (!container || !addButton || !template) {
        return;
    }

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

    const getItems = () => Array.from(container.querySelectorAll('[data-promo-item]'));

    const setInputRules = (item) => {
        const typeSelect = item.querySelector('[data-key="type"]');
        const discountInput = item.querySelector('[data-key="discount_value"]');

        if (!typeSelect || !discountInput) {
            return;
        }

        if (typeSelect.value === 'percent') {
            discountInput.max = '100';
            discountInput.placeholder = 'Contoh: 15';
        } else {
            discountInput.removeAttribute('max');
            discountInput.placeholder = 'Contoh: 15000';
        }
    };

    const updatePriceHint = (item) => {
        const productSelect = item.querySelector('[data-key="product_id"]');
        const typeSelect = item.querySelector('[data-key="type"]');
        const discountInput = item.querySelector('[data-key="discount_value"]');
        const hint = item.querySelector('[data-price-hint]');

        if (!productSelect || !typeSelect || !discountInput || !hint) {
            return;
        }

        const selectedProduct = productMap[productSelect.value];

        if (!selectedProduct) {
            hint.textContent = 'Pilih produk untuk melihat batas diskon aman berdasarkan harga beli.';
            discountInput.setCustomValidity('');
            return;
        }

        const purchasePrice = Number(selectedProduct.purchase_price || 0);
        const sellingPrice = Number(selectedProduct.selling_price || 0);
        const maxFixed = Math.max(sellingPrice - purchasePrice, 0);
        const maxPercent = sellingPrice > 0 ? Math.max((maxFixed / sellingPrice) * 100, 0) : 0;
        const discountValue = Number(discountInput.value || 0);

        if (typeSelect.value === 'percent') {
            const safePercent = Number(maxPercent.toFixed(2));
            hint.textContent = `Harga beli ${formatRupiah(purchasePrice)} | harga jual ${formatRupiah(sellingPrice)} | maksimal diskon aman ${safePercent}%`;
            if (discountValue > safePercent + 0.0001) {
                discountInput.setCustomValidity('Diskon terlalu besar. Harga setelah diskon tidak boleh di bawah harga beli.');
            } else {
                discountInput.setCustomValidity('');
            }
        } else {
            hint.textContent = `Harga beli ${formatRupiah(purchasePrice)} | harga jual ${formatRupiah(sellingPrice)} | maksimal potongan aman ${formatRupiah(maxFixed)}`;
            if (discountValue > maxFixed + 0.0001) {
                discountInput.setCustomValidity('Potongan terlalu besar. Harga setelah diskon tidak boleh di bawah harga beli.');
            } else {
                discountInput.setCustomValidity('');
            }
        }
    };

    const updateRemoveButtons = () => {
        const hasSingleItem = getItems().length <= 1;

        getItems().forEach((item) => {
            const removeButton = item.querySelector('[data-remove-product]');
            if (!removeButton) {
                return;
            }

            removeButton.disabled = hasSingleItem;
            removeButton.classList.toggle('opacity-50', hasSingleItem);
            removeButton.classList.toggle('cursor-not-allowed', hasSingleItem);
        });
    };

    const reindexItems = () => {
        getItems().forEach((item, index) => {
            const numberEl = item.querySelector('[data-item-number]');
            if (numberEl) {
                numberEl.textContent = String(index + 1);
            }

            item.querySelectorAll('[data-key]').forEach((field) => {
                field.name = `products[${index}][${field.dataset.key}]`;
            });
        });

        updateRemoveButtons();
    };

    const bindItemEvents = (item) => {
        const productSelect = item.querySelector('[data-key="product_id"]');
        const typeSelect = item.querySelector('[data-key="type"]');
        const discountInput = item.querySelector('[data-key="discount_value"]');
        const removeButton = item.querySelector('[data-remove-product]');

        if (productSelect) {
            productSelect.addEventListener('change', function () {
                updatePriceHint(item);
            });
        }

        if (typeSelect) {
            typeSelect.addEventListener('change', function () {
                setInputRules(item);
                updatePriceHint(item);
            });
        }

        if (discountInput) {
            discountInput.addEventListener('input', function () {
                updatePriceHint(item);
            });
        }

        if (removeButton) {
            removeButton.addEventListener('click', function () {
                if (getItems().length <= 1) {
                    return;
                }

                item.remove();
                reindexItems();
            });
        }

        setInputRules(item);
        updatePriceHint(item);
    };

    const addProductRow = () => {
        const nextIndex = getItems().length;
        const rowMarkup = template.innerHTML.replaceAll('__INDEX__', String(nextIndex)).trim();
        if (!rowMarkup) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.innerHTML = rowMarkup;
        const newItem = wrapper.firstElementChild;

        if (!newItem) {
            return;
        }

        container.appendChild(newItem);
        bindItemEvents(newItem);
        reindexItems();
        newItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    getItems().forEach((item) => bindItemEvents(item));
    reindexItems();

    addButton.addEventListener('click', addProductRow);
});
</script>
@endpush
