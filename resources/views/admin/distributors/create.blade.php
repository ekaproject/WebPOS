@extends('layouts.admin')

@section('title', 'Tambah Distributor')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.distributors.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Tambah Distributor</h1>
            <p class="text-on-surface-variant mt-0.5">Isi detail distributor baru di bawah ini</p>
        </div>
    </div>

    <form action="{{ route('admin.distributors.store') }}" method="POST"
          class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf

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
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('name') border-error @enderror"
                           placeholder="Cth: PT Indofood Sukses Makmur"/>
                    @error('name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="code">
                        Kode Distributor <span class="text-error">*</span>
                    </label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required
                           class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('code') border-error @enderror"
                           placeholder="Cth: DIST-001"/>
                    @error('code')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="contact_person">
                        Kontak Person
                    </label>
                    <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person') }}"
                           class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                           placeholder="Cth: Pak Budi"/>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="phone">
                        No. Telepon
                    </label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                           class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                           placeholder="Cth: 08123456789"/>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="email">
                        Email
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary @error('email') border-error @enderror"
                           placeholder="Cth: distributor@email.com"/>
                    @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5" for="address">
                        Alamat
                    </label>
                    <textarea id="address" name="address" rows="2"
                              class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                              placeholder="Cth: Jl. Sudirman No. 10, Jakarta Pusat">{{ old('address') }}</textarea>
                </div>

                <div class="md:col-span-2 flex items-center gap-3">
                    <input type="checkbox" id="is_active" name="is_active" value="1"
                           {{ old('is_active', '1') ? 'checked' : '' }}
                           class="rounded border-outline-variant text-primary focus:ring-primary"/>
                    <label for="is_active" class="text-sm font-medium">Aktif</label>
                </div>
            </div>
        </div>

        {{-- Produk --}}
        <div class="border-t border-outline-variant/10 pt-6">
            <h2 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">inventory_2</span>
                Produk yang Disupply
            </h2>
            <p class="text-xs text-on-surface-variant mb-4">Tambahkan produk beserta harga beli dari distributor ini. Bisa diisi nanti.</p>

            <div id="product-rows" class="space-y-3">
                {{-- Dynamic rows inserted here --}}
            </div>

            <button type="button" onclick="addProductRow()"
                    class="mt-3 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-fixed text-primary text-sm font-bold hover:bg-primary hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-lg">add</span>
                Tambah Produk
            </button>
        </div>

        <div class="flex gap-3 pt-2 border-t border-outline-variant/10">
            <button type="submit"
                    class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary"
                    style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
                Simpan Distributor
            </button>
            <a href="{{ route('admin.distributors.index') }}"
               class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
    const allProducts = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'sku' => $p->sku]));
    let rowIndex = 0;

    function addProductRow(productId = '', purchasePrice = '') {
        const container = document.getElementById('product-rows');
        const div = document.createElement('div');
        div.className = 'flex flex-col md:flex-row gap-3 items-start md:items-end';
        div.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Produk</label>
                <select name="products[${rowIndex}][id]" required
                        class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                    <option value="">-- Pilih Produk --</option>
                    ${allProducts.map(p => `<option value="${p.id}" ${p.id == productId ? 'selected' : ''}>${p.name} (${p.sku})</option>`).join('')}
                </select>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Harga Beli (Rp)</label>
                <input type="number" name="products[${rowIndex}][purchase_price]" value="${purchasePrice}" required min="0"
                       class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"
                       placeholder="0"/>
            </div>
            <button type="button" onclick="this.closest('.flex').remove()"
                    class="p-2.5 rounded-xl bg-error-container hover:bg-error hover:text-on-error text-on-error-container transition-colors flex-none">
                <span class="material-symbols-outlined text-base">close</span>
            </button>
        `;
        container.appendChild(div);
        rowIndex++;
    }
</script>
@endsection
