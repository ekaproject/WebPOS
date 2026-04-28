@php
    $row = $row ?? [];
    $currentType = $row['type'] ?? 'fixed';
@endphp

<div data-promo-item class="rounded-2xl border border-outline-variant/20 bg-surface-container-lowest p-5 space-y-4">
    <div class="flex items-center justify-between gap-3">
        <p class="text-sm font-bold text-on-surface">Produk Promo <span data-item-number>{{ is_numeric($index) ? ((int) $index + 1) : '-' }}</span></p>
        <button type="button" data-remove-product
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-error bg-error-container/60 hover:bg-error hover:text-on-error transition-colors">
            <span class="material-symbols-outlined text-sm">delete</span>
            Hapus Produk
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4">
        <div class="xl:col-span-2">
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Produk <span class="text-error">*</span></label>
            <select data-key="product_id" name="products[{{ $index }}][product_id]"
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('products.'.$index.'.product_id') border-error @enderror">
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ (string) ($row['product_id'] ?? '') === (string) $product->id ? 'selected' : '' }}>
                        {{ $product->name }} ({{ $product->category?->name ?? 'Tanpa Kategori' }})
                    </option>
                @endforeach
            </select>
            @error('products.'.$index.'.product_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Jenis Promo <span class="text-error">*</span></label>
            <select data-key="type" name="products[{{ $index }}][type]"
                    class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('products.'.$index.'.type') border-error @enderror">
                <option value="fixed" {{ $currentType === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                <option value="percent" {{ $currentType === 'percent' ? 'selected' : '' }}>Persen (%)</option>
            </select>
            @error('products.'.$index.'.type')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Nilai Diskon <span class="text-error">*</span></label>
            <input type="number" step="0.01" min="0.01" data-key="discount_value"
                   value="{{ $row['discount_value'] ?? '' }}"
                   name="products[{{ $index }}][discount_value]"
                   class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('products.'.$index.'.discount_value') border-error @enderror"
                   placeholder="{{ $currentType === 'percent' ? 'Contoh: 15' : 'Contoh: 15000' }}"/>
            @error('products.'.$index.'.discount_value')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Tanggal Mulai <span class="text-error">*</span></label>
            <input type="date" data-key="start_date"
                   value="{{ $row['start_date'] ?? '' }}"
                   name="products[{{ $index }}][start_date]"
                   class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('products.'.$index.'.start_date') border-error @enderror"/>
            @error('products.'.$index.'.start_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Tanggal Selesai <span class="text-error">*</span></label>
            <input type="date" data-key="end_date"
                   value="{{ $row['end_date'] ?? '' }}"
                   name="products[{{ $index }}][end_date]"
                   class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('products.'.$index.'.end_date') border-error @enderror"/>
            @error('products.'.$index.'.end_date')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <p data-price-hint class="text-[11px] text-on-surface-variant"></p>
</div>
