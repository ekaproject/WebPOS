@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Form Pengaturan</h1>
            <p class="text-on-surface-variant mt-1">Atur identitas toko dan parameter umum yang dipakai pada Landing Page Website.</p>
        </div>
        <div class="flex items-center gap-2">
            <button id="settings-edit-btn" type="button" class="w-10 h-10 rounded-xl inline-flex items-center justify-center text-white shadow-sm" style="background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);" title="Edit Pengaturan" aria-label="Edit Pengaturan">
                <span class="material-symbols-outlined text-[20px]">edit</span>
            </button>
            <button id="settings-cancel-btn" type="button" class="hidden px-5 py-2.5 rounded-xl text-sm font-bold bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </button>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Nama Toko</label>
                <input data-settings-field type="text" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" required class="w-full rounded-xl border border-outline-variant/80 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                @error('store_name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Email Toko</label>
                <input data-settings-field type="email" name="store_email" value="{{ old('store_email', $settings['store_email']) }}" required class="w-full rounded-xl border border-outline-variant/80 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                @error('store_email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">No. Telepon Toko</label>
                <input data-settings-field type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone']) }}" class="w-full rounded-xl border border-outline-variant/80 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                @error('store_phone')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Pajak (%)</label>
                <input data-settings-field type="number" step="0.01" min="0" max="100" name="tax_percent" value="{{ old('tax_percent', $settings['tax_percent'] ?? 11) }}" required class="w-full rounded-xl border border-outline-variant/80 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                @error('tax_percent')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Alamat Toko</label>
                <textarea data-settings-field name="store_address" rows="3" class="w-full rounded-xl border border-outline-variant/80 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary">{{ old('store_address', $settings['store_address']) }}</textarea>
                @error('store_address')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Teks Footer Aplikasi</label>
                <input data-settings-field type="text" name="footer_text" value="{{ old('footer_text', $settings['footer_text']) }}" class="w-full rounded-xl border border-outline-variant/80 bg-surface-container-low text-sm focus:ring-2 focus:ring-primary"/>
                @error('footer_text')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div id="settings-save-wrap" class="hidden pt-4 border-t border-outline-variant/10">
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);">Simpan Pengaturan</button>
        </div>
    </form>
    <p id="settings-readonly-hint" class="text-xs text-on-surface-variant">Mode baca aktif. Klik ikon pensil untuk mulai mengedit.</p>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fields = Array.from(document.querySelectorAll('[data-settings-field]'));
    const editBtn = document.getElementById('settings-edit-btn');
    const cancelBtn = document.getElementById('settings-cancel-btn');
    const saveWrap = document.getElementById('settings-save-wrap');
    const readonlyHint = document.getElementById('settings-readonly-hint');
    const hasValidationError = {{ $errors->any() ? 'true' : 'false' }};

    const setEditMode = (enabled) => {
        fields.forEach((field) => {
            field.readOnly = !enabled;
            if (field.tagName === 'SELECT') {
                field.disabled = !enabled;
            }
            field.classList.toggle('opacity-70', !enabled);
            field.classList.toggle('cursor-not-allowed', !enabled);
            field.classList.toggle('bg-surface-container-high', !enabled);
            field.classList.toggle('bg-surface-container-low', enabled);
            field.classList.toggle('border-outline-variant', !enabled);
            field.classList.toggle('border-primary/60', enabled);
        });

        editBtn.classList.toggle('hidden', enabled);
        cancelBtn.classList.toggle('hidden', !enabled);
        saveWrap.classList.toggle('hidden', !enabled);
        readonlyHint.classList.toggle('hidden', enabled);
    };

    setEditMode(hasValidationError);

    editBtn.addEventListener('click', function () {
        setEditMode(true);
    });

    cancelBtn.addEventListener('click', function () {
        window.location.reload();
    });
});
</script>
@endpush
@endsection
