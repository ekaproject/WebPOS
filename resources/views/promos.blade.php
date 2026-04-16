<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Promo Harian | {{ $publicSettings['store_name'] }}</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @include('partials.vite-assets')
</head>
<body class="bg-surface font-body text-on-surface">

@include('partials.navbar', [
    'active' => 'promos',
    'authVariant' => 'logout',
])

<main class="pt-24">
    <section class="px-6 md:px-10 py-10 bg-surface-container-low">
        <p class="text-sm text-white font-semibold uppercase tracking-[0.2em] inline-flex items-center gap-2 px-4 py-1 rounded-full"
           style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
            Halaman Promo
        </p>
        <h1 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tight text-primary mt-3">Promo Potongan Harga & Voucher</h1>
        <p class="text-on-surface-variant mt-3 max-w-3xl">Nikmati promo potongan harga nominal dan voucher belanja dengan syarat minimum pembelian. Penawaran diperbarui secara berkala sesuai periode promo aktif.</p>

        <form action="{{ route('promos.index') }}" method="GET" class="mt-6 flex flex-col md:flex-row gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari promo, kategori, atau deskripsi..."
                   class="w-full md:w-[420px] rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary"/>
            <div class="rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-sm font-semibold text-on-surface">
                Tipe Promo: Potongan Nominal
            </div>
                <button type="submit" class="px-6 py-3 rounded-xl text-white font-bold text-sm hover:brightness-110 transition-all"
                    style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">Filter Promo</button>
        </form>

        <div class="mt-6 bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-5">
            <h3 class="text-lg font-headline font-bold text-primary">Klaim Kode Voucher</h3>
            <p class="text-sm text-on-surface-variant mt-1">Klaim voucher dilakukan melalui aplikasi mobile. Halaman web hanya menampilkan informasi promo voucher aktif.</p>
        </div>
    </section>

    <section class="px-6 md:px-10 py-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-headline font-extrabold text-primary">Promo Produk Harian</h2>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#0284C7]/15 text-[#0284C7]">{{ $dailyPromos->count() }} Promo</span>
        </div>

        @if($dailyPromos->isEmpty())
            <div class="bg-surface-container-low rounded-2xl border border-outline-variant/20 p-8 text-center text-on-surface-variant">
                Belum ada promo produk harian aktif saat ini.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($dailyPromos as $promo)
                    <article class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full text-white"
                                    style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">Promo Harian</span>
                            <span class="text-xs font-semibold text-on-surface-variant">Sampai {{ $promo->end_date->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-xl font-headline font-extrabold text-on-surface">{{ $promo->title }}</h3>
                        <p class="text-sm text-on-surface-variant mt-2">{{ $promo->description ?: 'Promo spesial untuk produk pilihan hari ini.' }}</p>
                        <div class="mt-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-on-surface-variant uppercase tracking-wide">Diskon</p>
                                <p class="text-2xl font-extrabold text-primary">{{ $promo->discount_label }}</p>
                            </div>
                            @if($promo->category)
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#0284C7]/15 text-[#0284C7]">{{ $promo->category->name }}</span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <section id="promo-products" class="px-6 md:px-10 py-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-headline font-extrabold text-primary">Daftar Produk Promo</h2>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#0284C7]/15 text-[#0284C7]">{{ $productPromos->count() }} Produk</span>
        </div>

        @if($productPromos->isEmpty())
            <div class="bg-surface-container-low rounded-2xl border border-outline-variant/20 p-8 text-center text-on-surface-variant">
                Belum ada produk promo aktif saat ini.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($productPromos as $promo)
                    @if($promo->product)
                        <article class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden">
                            <div class="h-44 bg-surface-container relative">
                                <div class="absolute top-3 left-3 z-10">
                                    @if($promo->start_date->isFuture())
                                        <span class="text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase"
                                              style="background: linear-gradient(135deg, #003366 0%, #0284C7 100%);">Mulai {{ $promo->start_date->format('d M') }}</span>
                                    @else
                                        <span class="text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase"
                                              style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">Aktif</span>
                                    @endif
                                </div>
                                @if($promo->product->image)
                                    <img src="{{ asset('storage/'.$promo->product->image) }}" alt="{{ $promo->product->name }}" class="w-full h-full object-cover"/>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="material-symbols-outlined text-7xl text-primary/20">inventory_2</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-xs font-bold uppercase tracking-wide text-primary">{{ $promo->product->category?->name ?? 'Produk' }}</p>
                                <h3 class="text-lg font-headline font-bold mt-1">{{ $promo->product->name }}</h3>
                                <p class="text-sm text-on-surface-variant mt-2 line-clamp-2">{{ $promo->title }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">Periode: {{ $promo->start_date->format('d M Y') }} - {{ $promo->end_date->format('d M Y') }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-xl font-extrabold text-primary">{{ $promo->discount_label }}</span>
                                    <a href="{{ $promo->product->category ? route('categories.show', $promo->product->category->slug) : route('categories.index') }}" class="bg-primary/10 text-primary p-2 rounded-xl hover:bg-primary hover:text-on-primary transition-colors inline-flex">
                                        <span class="material-symbols-outlined">arrow_outward</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endif
                @endforeach
            </div>
        @endif
    </section>

    <section class="px-6 md:px-10 py-12 bg-surface-container-low">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-headline font-extrabold text-primary">Voucher Promo</h2>
            <span class="text-xs font-bold px-3 py-1 rounded-full bg-[#0284C7]/15 text-[#0284C7]">{{ $voucherPromos->count() }} Voucher</span>
        </div>

        @if($voucherPromos->isEmpty())
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-8 text-center text-on-surface-variant">
                Belum ada voucher promo aktif saat ini.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($voucherPromos as $promo)
                    <article class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-6 relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-[#0284C7]/15"></div>
                        <div class="relative">
                            <span class="inline-block text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full text-white"
                                  style="background: linear-gradient(135deg, #003366 0%, #22C55E 100%);">Voucher</span>
                            <h3 class="text-xl font-headline font-extrabold text-on-surface mt-3">{{ $promo->title }}</h3>
                            <p class="text-sm text-on-surface-variant mt-2">{{ $promo->description ?: 'Voucher promo belanja dengan syarat minimum pembelian.' }}</p>

                            <div class="mt-5 grid grid-cols-2 gap-4">
                                <div class="bg-surface-container p-4 rounded-xl">
                                    <p class="text-xs text-on-surface-variant uppercase tracking-wide">Minimum Belanja</p>
                                    <p class="text-lg font-extrabold text-primary">Rp {{ number_format((float) $promo->min_purchase, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-surface-container p-4 rounded-xl">
                                    <p class="text-xs text-on-surface-variant uppercase tracking-wide">Benefit</p>
                                    <p class="text-lg font-extrabold text-primary">{{ $promo->discount_label }}</p>
                                </div>
                                <div class="bg-surface-container p-4 rounded-xl">
                                    <p class="text-xs text-on-surface-variant uppercase tracking-wide">Kode Voucher</p>
                                    <p class="text-lg font-extrabold text-primary">{{ $promo->voucher_code ?? '-' }}</p>
                                </div>
                                <div class="bg-surface-container p-4 rounded-xl">
                                    <p class="text-xs text-on-surface-variant uppercase tracking-wide">Sisa Kuota</p>
                                    <p class="text-lg font-extrabold {{ ($promo->voucher_remaining ?? 0) > 0 ? 'text-secondary' : 'text-error' }}">{{ $promo->voucher_remaining ?? 0 }}</p>
                                </div>
                            </div>

                            <p class="text-xs text-on-surface-variant mt-4">Berlaku: {{ $promo->start_date->format('d M Y') }} - {{ $promo->end_date->format('d M Y') }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
</main>

<footer class="bg-inverse-surface text-inverse-on-surface mt-16 px-6 md:px-10 py-8 text-center text-sm">
    <p>&copy; {{ date('Y') }} {{ $publicSettings['store_name'] }}. {{ $publicSettings['footer_text'] }}</p>
</footer>

</body>
</html>
