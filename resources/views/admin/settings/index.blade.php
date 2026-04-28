@extends('layouts.admin')

@section('title', 'Konten')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-headline font-extrabold text-primary">Form Konten</h1>
            <p class="text-on-surface-variant mt-1">Atur identitas toko dan parameter umum yang dipakai pada Landing Page Website.</p>
        </div>
        <div class="flex items-center gap-2">
            <button id="settings-edit-btn" type="button" class="w-10 h-10 rounded-xl inline-flex items-center justify-center text-white shadow-sm" style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);" title="Edit Konten" aria-label="Edit Konten">
                <span class="material-symbols-outlined text-[20px]">edit</span>
            </button>
            <button id="settings-cancel-btn" type="button" class="hidden px-5 py-2.5 rounded-xl text-sm font-bold bg-surface-container text-on-surface-variant hover:bg-surface-container-high transition-colors">
                Batal
            </button>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-surface-container-lowest border border-outline-variant/20 rounded-2xl p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Nama Toko</label>
                <input data-settings-field type="text" name="store_name" value="{{ old('store_name', $settings['store_name']) }}" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('store_name')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Email Toko</label>
                <input data-settings-field type="email" name="store_email" value="{{ old('store_email', $settings['store_email']) }}" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('store_email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">No. Telepon Toko</label>
                <input data-settings-field type="text" name="store_phone" value="{{ old('store_phone', $settings['store_phone']) }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('store_phone')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Pajak (%)</label>
                <input data-settings-field type="number" step="0.01" min="0" max="100" name="tax_percent" value="{{ old('tax_percent', $settings['tax_percent'] ?? 11) }}" required class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('tax_percent')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Alamat Toko</label>
                <textarea data-settings-field name="store_address" rows="3" class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary">{{ old('store_address', $settings['store_address']) }}</textarea>
                @error('store_address')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-1.5">Teks Footer Aplikasi</label>
                <input data-settings-field type="text" name="footer_text" value="{{ old('footer_text', $settings['footer_text'] ?? '') }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-white text-sm focus:ring-2 focus:ring-primary"/>
                @error('footer_text')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="md:col-span-2 mt-8 mb-4 border-b border-outline-variant/20 pb-2">
                <h3 class="text-lg font-headline font-bold text-primary">Konten Landing Page</h3>
                <p class="text-xs text-on-surface-variant">Atur teks yang ditampilkan pada halaman utama website.</p>
            </div>

            <div class="md:col-span-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 space-y-4">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-primary mb-1.5">Hero Title (Judul Banner Utama)</label>
                    <input data-settings-field type="text" name="landing_hero_title" value="{{ old('landing_hero_title', $settings['landing_hero_title'] ?? '') }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary"/>
                    @error('landing_hero_title')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-primary mb-1.5">Hero Description (Deskripsi Banner Utama)</label>
                    <textarea data-settings-field name="landing_hero_description" rows="2" class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary">{{ old('landing_hero_description', $settings['landing_hero_description'] ?? '') }}</textarea>
                    @error('landing_hero_description')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="md:col-span-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 space-y-4">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-secondary mb-1.5">Title Fitur / Solusi</label>
                    <input data-settings-field type="text" name="landing_solusi_text" value="{{ old('landing_solusi_text', $settings['landing_solusi_text'] ?? '') }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary"/>
                    @error('landing_solusi_text')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-secondary mb-1.5">Deskripsi Fitur / Solusi</label>
                    <textarea data-settings-field name="landing_solusi_desc" rows="2" class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary">{{ old('landing_solusi_desc', $settings['landing_solusi_desc'] ?? '') }}</textarea>
                    @error('landing_solusi_desc')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 space-y-4">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-tertiary mb-1.5">Fitur Poin 1 (Judul)</label>
                    <input data-settings-field type="text" name="landing_feature_1_title" value="{{ old('landing_feature_1_title', $settings['landing_feature_1_title'] ?? '') }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary"/>
                    @error('landing_feature_1_title')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-tertiary mb-1.5">Fitur Poin 1 (Deskripsi)</label>
                    <textarea data-settings-field name="landing_feature_1_desc" rows="2" class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary">{{ old('landing_feature_1_desc', $settings['landing_feature_1_desc'] ?? '') }}</textarea>
                    @error('landing_feature_1_desc')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 space-y-4">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-tertiary mb-1.5">Fitur Poin 2 (Judul)</label>
                    <input data-settings-field type="text" name="landing_feature_2_title" value="{{ old('landing_feature_2_title', $settings['landing_feature_2_title'] ?? '') }}" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary"/>
                    @error('landing_feature_2_title')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold tracking-wider text-tertiary mb-1.5">Fitur Poin 2 (Deskripsi)</label>
                    <textarea data-settings-field name="landing_feature_2_desc" rows="2" class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary">{{ old('landing_feature_2_desc', $settings['landing_feature_2_desc'] ?? '') }}</textarea>
                    @error('landing_feature_2_desc')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="md:col-span-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 space-y-4">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-primary mb-1.5">Deskripsi Tentang Perusahaan (Footer)</label>
                    <textarea data-settings-field name="landing_about_desc" rows="2" class="w-full min-h-[100px] px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm focus:ring-2 focus:ring-primary">{{ old('landing_about_desc', $settings['landing_about_desc'] ?? '') }}</textarea>
                    @error('landing_about_desc')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="md:col-span-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/30 space-y-4">
                <div>
                    <label class="block text-xs font-bold tracking-wider text-primary mb-1.5">Foto Lokasi Kami (Landing Page)</label>
                    <p class="text-xs text-on-surface-variant">Unggah 3 foto untuk kolom kanan modul Lokasi Kami. Format: JPG, PNG, WEBP. Maks. 3MB per foto.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Foto 1</label>
                        @if(!empty($settings['landing_location_photo_1']))
                            <img src="{{ asset('storage/'.$settings['landing_location_photo_1']) }}" alt="Foto Lokasi 1" class="w-full h-32 object-cover rounded-xl border border-outline-variant/40">
                        @endif
                        <input data-settings-field type="file" name="landing_location_photo_1" accept="image/*" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-primary"/>
                        @error('landing_location_photo_1')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Foto 2</label>
                        @if(!empty($settings['landing_location_photo_2']))
                            <img src="{{ asset('storage/'.$settings['landing_location_photo_2']) }}" alt="Foto Lokasi 2" class="w-full h-32 object-cover rounded-xl border border-outline-variant/40">
                        @endif
                        <input data-settings-field type="file" name="landing_location_photo_2" accept="image/*" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-primary"/>
                        @error('landing_location_photo_2')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-on-surface-variant">Foto 3</label>
                        @if(!empty($settings['landing_location_photo_3']))
                            <img src="{{ asset('storage/'.$settings['landing_location_photo_3']) }}" alt="Foto Lokasi 3" class="w-full h-32 object-cover rounded-xl border border-outline-variant/40">
                        @endif
                        <input data-settings-field type="file" name="landing_location_photo_3" accept="image/*" class="w-full h-11 px-4 py-2.5 leading-normal rounded-xl border border-outline-variant/30 bg-whiteest text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-primary/10 file:px-3 file:py-2 file:text-primary"/>
                        @error('landing_location_photo_3')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div id="settings-save-wrap" class="hidden pt-4 border-t border-outline-variant/10">
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white" style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">Simpan Konten</button>
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
            if (field.type === 'file') {
                field.disabled = !enabled;
            }

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
