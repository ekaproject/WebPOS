@extends('layouts.admin')

@section('title', 'Retur Barang ke Distributor')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('admin.distributors.index') }}" class="p-2 rounded-xl bg-surface-container hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h1 class="text-2xl font-bold text-primary">Retur Barang ke Distributor</h1>
    </div>
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-100 text-green-800">{{ session('success') }}</div>
    @endif
    <form action="{{ route('admin.returns.store') }}" method="POST" class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 space-y-6">
        @csrf
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Distributor<span class="text-error">*</span></label>
            <select name="distributor_id" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">
                <option value="">-- Pilih Distributor --</option>
                @foreach($distributors as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Tanggal Retur<span class="text-error">*</span></label>
            <input type="date" name="return_date" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Catatan</label>
            <textarea name="note" rows="2" class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"></textarea>
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Produk yang Diretur<span class="text-error">*</span></label>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border border-outline-variant/20 rounded-xl overflow-hidden min-w-[700px]">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="p-2">Produk</th>
                            <th class="p-2">Qty</th>
                            <th class="p-2">Harga Beli</th>
                            <th class="p-2">Catatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="items-table">
                        <tr>
                            <td>
                                <select name="items[0][product_id]" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm product-select">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach($products as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->sku }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="items[0][quantity]" min="1" value="1" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm"/></td>
                            <td><input type="number" name="items[0][purchase_price]" min="0" value="0" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm purchase-price-input"/></td>
                            <td><input type="text" name="items[0][note]" class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm"/></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button type="button" id="add-item" class="mt-2 px-4 py-2 rounded-xl bg-primary text-on-primary text-xs font-bold">+ Tambah Produk</button>
            <input type="hidden" id="all-products-json" value='@json($products->map(fn($p) => ["id"=>$p->id,"name"=>$p->name,"sku"=>$p->sku]))'>
        </div>
        <div class="flex items-center gap-4 pt-4 border-t border-outline-variant/10">
            <button type="submit" class="px-8 py-3 rounded-xl font-bold text-sm text-on-primary" style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">Simpan Retur</button>
            <a href="{{ route('admin.distributors.index') }}" class="px-8 py-3 rounded-xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">Batal</a>
        </div>
    </form>
</div>
@push('scripts')
<script>
let rowIndex = 1;
const distributorSelect = document.querySelector('select[name="distributor_id"]');
let distributorProducts = [];

function updateAllProductSelects() {
    document.querySelectorAll('.product-select').forEach(function(select, idx) {
        const selected = select.value;
        select.innerHTML = '<option value="">-- Pilih Produk --</option>' +
            distributorProducts.map(p => `<option value="${p.id}" data-purchase-price="${p.purchase_price ?? 0}" ${p.id == selected ? 'selected' : ''}>${p.name} (${p.sku})</option>`).join('');
        // Auto update purchase price if available
        const priceInput = select.closest('tr').querySelector('.purchase-price-input');
        if (priceInput && select.value) {
            const found = distributorProducts.find(p => p.id == select.value);
            if (found) priceInput.value = found.purchase_price ?? 0;
        }
    });
}

distributorSelect.addEventListener('change', function() {
    const distributorId = this.value;
    if (!distributorId) {
        distributorProducts = [];
        updateAllProductSelects();
        return;
    }
    fetch(`/api/distributor/${distributorId}/products`)
        .then(res => res.json())
        .then(data => {
            distributorProducts = data.products || [];
            updateAllProductSelects();
        });
});

document.getElementById('add-item').addEventListener('click', function() {
    const table = document.getElementById('items-table');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td>
            <select name="items[${rowIndex}][product_id]" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm product-select">
                <option value="">-- Pilih Produk --</option>
            </select>
        </td>
        <td><input type="number" name="items[${rowIndex}][quantity]" min="1" value="1" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm"/></td>
        <td><input type="number" name="items[${rowIndex}][purchase_price]" min="0" value="0" required class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm purchase-price-input"/></td>
        <td><input type="text" name="items[${rowIndex}][note]" class="w-full rounded-xl border border-outline-variant/30 bg-white text-sm"/></td>
        <td><button type="button" onclick="this.closest('tr').remove()" class="text-error font-bold">Hapus</button></td>
    `;
    table.appendChild(row);
    rowIndex++;
    updateAllProductSelects();
});

// Auto update purchase price when product selected
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const priceInput = e.target.closest('tr').querySelector('.purchase-price-input');
        const selected = e.target.options[e.target.selectedIndex];
        if (priceInput && selected && selected.dataset.purchasePrice !== undefined) {
            priceInput.value = selected.dataset.purchasePrice;
        }
    }
});
</script>
@endpush
@endsection
