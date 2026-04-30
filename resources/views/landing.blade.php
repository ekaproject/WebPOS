<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $publicSettings['store_name'] }} | Solusi Lengkap Belanja Keluarga</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @include('partials.vite-assets')
</head>
<body class="landing-surface font-body text-on-surface">

<!-- Top Navigation -->
@include('partials.navbar', [
    'active' => 'home',
    'showSearch' => true,
    'searchAction' => route('categories.index'),
    'searchPlaceholder' => 'Cari produk atau kategori...',
    'authVariant' => 'logout',
])

<main class="pt-6 md:pt-8">
    <!-- Hero Section -->
    <section class="px-5 md:px-10 pt-4 md:pt-6 pb-10 md:pb-12">
        @php
            $heroPromoSlides = [];

            foreach (($promos ?? collect()) as $promo) {
                if (!empty($promo->image)) {
                    $heroPromoSlides[] = [
                        'image' => asset('storage/'.$promo->image),
                        'title' => $promo->title,
                        'badge' => $promo->discount_label,
                    ];
                }
            }

            if (count($heroPromoSlides) === 0) {
                foreach (($featuredProducts ?? collect()) as $promoProduct) {
                    $product = $promoProduct->product;
                    if ($product && !empty($product->image)) {
                        $heroPromoSlides[] = [
                            'image' => asset('storage/'.$product->image),
                            'title' => $product->name,
                            'badge' => 'Promo Aktif',
                        ];
                    }
                }
            }

            $activeCategoriesCount = ($categories ?? collect())->count();
            $activePromoCount = ($promos ?? collect())->count();
        @endphp
        <div class="hero-gradient-shell min-h-[560px] md:min-h-[620px]">
            <div class="absolute top-24 right-14 w-44 h-44 rounded-full bg-white/10 blur-2xl hero-floating-orb"></div>
            <div class="absolute bottom-14 left-10 w-56 h-56 rounded-full bg-cyan-200/20 blur-3xl hero-floating-orb"></div>

            <div class="relative z-10 grid grid-cols-1 xl:grid-cols-[1.08fr_0.92fr] gap-8 lg:gap-10 items-center p-6 md:p-10 lg:p-14">
                <div class="max-w-2xl fade-rise">
                    <span class="inline-flex px-4 py-1.5 rounded-full bg-white/18 text-white font-headline text-xs font-bold mb-6 tracking-[0.2em] uppercase border border-white/30 backdrop-blur-sm shadow-sm">
                        {{ $publicSettings['landing_solusi_text'] ?? 'Solusi Belanja Terlengkap' }}
                    </span>
                    <h1 class="text-4xl md:text-6xl lg:text-[70px] font-headline font-extrabold text-white leading-[1.08] tracking-tight mb-5">
                        {{ $publicSettings['landing_hero_title'] ?? 'Satu Tempat untuk Semua Kebutuhan.' }}
                    </h1>
                    <p class="text-base md:text-lg text-white/90 mb-8 max-w-xl font-medium leading-relaxed">
                        {{ $publicSettings['landing_hero_description'] ?? 'Mulai dari bahan makanan segar, perlengkapan rumah tangga, hingga bayar tagihan. Belanja cerdas, hidup lebih berkualitas.' }}
                    </p>
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 md:gap-4">
                        <a href="{{ route('categories.index') }}" class="landing-btn">
                            Jelajahi Kategori Sekarang
                            <span class="material-symbols-outlined">trending_flat</span>
                        </a>
                        <a href="{{ route('promos.index') }}" class="landing-btn-soft">
                            Lihat Promo Hari Ini
                        </a>
                    </div>

                    <div class="mt-7 grid grid-cols-2 sm:grid-cols-3 gap-3 max-w-lg">
                        <a href="{{ route('categories.index') }}" class="group rounded-2xl border border-white/25 bg-white/12 backdrop-blur-sm px-4 py-3 transition-all hover:border-white/50 hover:bg-white/18 hover:shadow-lg">
                            <p class="text-[11px] uppercase tracking-wider text-white/70 font-semibold group-hover:text-white/90 transition-colors">Kategori Aktif</p>
                            <p class="text-2xl font-black text-white mt-0.5">{{ $activeCategoriesCount }}</p>
                        </a>
                        <a href="{{ route('promos.index') }}" class="group rounded-2xl border border-white/25 bg-white/12 backdrop-blur-sm px-4 py-3 transition-all hover:border-white/50 hover:bg-white/18 hover:shadow-lg">
                            <p class="text-[11px] uppercase tracking-wider text-white/70 font-semibold group-hover:text-white/90 transition-colors">Promo Aktif</p>
                            <p class="text-2xl font-black text-white mt-0.5">{{ $activePromoCount }}</p>
                        </a>
                        <a href="#location-info" class="group rounded-2xl border border-white/25 bg-white/12 backdrop-blur-sm px-4 py-3 col-span-2 sm:col-span-1 transition-all hover:border-white/50 hover:bg-white/18 hover:shadow-lg">
                            <p class="text-[11px] uppercase tracking-wider text-white/70 font-semibold group-hover:text-white/90 transition-colors">Lokasi</p>
                            <p class="text-sm font-bold text-white mt-1 line-clamp-1">{{ $publicSettings['store_address'] }}</p>
                        </a>
                    </div>
                </div>

                <div class="w-full xl:max-w-[520px] xl:ml-auto fade-rise" style="animation-delay: 120ms;">
                    <div class="promo-glass-card border-white/30 bg-white/12 p-4 md:p-5">
                        <div class="flex items-center justify-between mb-3 px-1">
                            <p class="text-xs uppercase tracking-[0.2em] text-white/80 font-bold">Promo {{ $publicSettings['store_name'] ?? 'ILS Mart' }}</p>
                            <a href="{{ route('promos.index') }}" class="text-[11px] font-bold text-white/90 hover:text-white transition-colors underline-offset-4 hover:underline">Lihat Semua</a>
                        </div>

                        <div id="hero-promo-gallery" class="relative">
                            <div class="rounded-2xl overflow-hidden border border-white/25 bg-white/8 h-[340px] sm:h-[380px] md:h-[420px]">
                                <div id="hero-promo-gallery-track" class="flex h-full transition-transform duration-700 ease-out transition-opacity duration-300 opacity-100">
                                    @forelse($heroPromoSlides as $index => $slide)
                                        <div class="w-full h-full flex-none relative">
                                            <img src="{{ $slide['image'] }}" alt="Promo {{ $index + 1 }}" class="w-full h-full object-cover"/>
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/15 to-transparent"></div>
                                            <div class="absolute left-4 right-4 bottom-4">
                                                <span class="inline-flex px-3 py-1 rounded-full bg-[#22C55E] text-white text-[11px] font-bold mb-2">{{ $slide['badge'] }}</span>
                                                <p class="text-white text-lg font-headline font-extrabold leading-tight">{{ $slide['title'] }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="w-full h-full flex-none">
                                            <div class="w-full h-full flex flex-col items-center justify-center text-white/85 gap-2">
                                                <span class="material-symbols-outlined text-5xl">local_offer</span>
                                                <p class="text-sm font-semibold">Belum ada foto promo</p>
                                                <p class="text-xs text-white/70">Tambahkan foto promo dari admin untuk tampil di sini.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            @if(count($heroPromoSlides) > 1)
                                <button type="button" id="hero-promo-gallery-prev" aria-label="Promo Sebelumnya" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/40 text-white inline-flex items-center justify-center hover:bg-black/60 transition-colors backdrop-blur-sm">
                                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                                </button>
                                <button type="button" id="hero-promo-gallery-next" aria-label="Promo Selanjutnya" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/40 text-white inline-flex items-center justify-center hover:bg-black/60 transition-colors backdrop-blur-sm">
                                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                </button>

                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2">
                                    @foreach($heroPromoSlides as $index => $slide)
                                        <button type="button" data-slide-to="{{ $index }}" class="w-2.5 h-2.5 rounded-full bg-white/55 hover:bg-white transition-colors" aria-label="Ke promo {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section id="categories" class="px-6 md:px-10 py-14">
        <div class="rounded-[2rem] border border-white/65 bg-white/70 backdrop-blur-sm p-6 md:p-8 shadow-[0_16px_36px_rgba(2,54,97,0.08)]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary/70">Belanja Lebih Mudah</p>
                    <h2 class="text-3xl md:text-4xl font-headline font-extrabold tracking-tight text-primary mt-1">Kategori Favorit</h2>
                </div>
                <a href="{{ route('categories.index') }}" class="landing-btn btn-inline">
                    Lihat Semua
                    <span class="material-symbols-outlined">trending_flat</span>
                </a>
            </div>

            <div class="flex gap-3 overflow-x-auto category-scroll pb-3">
                <button type="button" data-category-filter="all" class="nav-link-pill nav-link-pill-active flex-none">Semua</button>
                @foreach($categories as $cat)
                    <button type="button" data-category-filter="{{ $cat->slug }}" class="nav-link-pill bg-white border border-white/75 flex-none whitespace-nowrap flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">{{ $cat->icon }}</span>
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>

            <p id="landing-products-count" class="mt-5 text-sm text-on-surface-variant font-medium">
                Menampilkan {{ ($landingProducts ?? collect())->count() }} produk
            </p>

            @if(($landingProducts ?? collect())->isNotEmpty())
                <div id="landing-products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mt-5">
                    @foreach($landingProducts as $product)
                        @continue(!$product->category)
                        <article
                            data-product-card
                            data-product-category="{{ $product->category->slug }}"
                            class="promo-glass-card bg-white/88 border-white/80 overflow-hidden"
                        >
                            <div class="h-40 bg-gradient-to-br from-primary/18 to-secondary/12 relative overflow-hidden flex items-center justify-center">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover"/>
                                @else
                                    <span class="material-symbols-outlined text-7xl text-primary/25" style="font-variation-settings: 'FILL' 1;">{{ $product->category->icon ?: 'inventory_2' }}</span>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-primary font-bold uppercase tracking-wider mb-2">{{ $product->category->name }}</p>
                                <h3 class="text-base font-headline font-bold mb-2 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-sm text-on-surface-variant line-clamp-2">{{ $product->description ?: 'Produk aktif yang tersedia untuk pembelian.' }}</p>
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-lg font-extrabold text-primary">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</span>
                                        <span class="text-xs text-on-surface-variant">Stok: {{ $product->stock }} {{ $product->unit }}</span>
                                    </div>
                                    <a href="{{ route('categories.show', $product->category->slug) }}" class="landing-btn-neutral p-2.5 rounded-xl inline-flex">
                                        <span class="material-symbols-outlined">arrow_outward</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div id="landing-products-empty" class="hidden bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-10 text-center text-on-surface-variant mt-5">
                    Tidak ada produk aktif untuk kategori ini.
                </div>
            @else
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-10 text-center text-on-surface-variant mt-5">
                    Belum ada produk aktif saat ini. Produk dari admin akan tampil di sini.
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Products -->
    <section id="promo" class="px-6 md:px-10 pb-16">
        <div class="rounded-[2rem] border border-white/60 bg-white/70 backdrop-blur-sm p-6 md:p-8 shadow-[0_16px_36px_rgba(2,54,97,0.08)]">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary/70">Pilihan Spesial</p>
                    <h2 class="text-3xl md:text-4xl font-headline font-extrabold tracking-tight text-primary mt-1">Promo Produk Hari Ini</h2>
                    <p class="text-on-surface-variant font-medium mt-2">Menampilkan produk yang sedang promo dengan harga terbaik.</p>
                </div>
                <a class="landing-btn btn-inline" href="{{ route('promos.index') }}#promo-products">
                    Lihat Semua
                    <span class="material-symbols-outlined">trending_flat</span>
                </a>
            </div>

            @if(($featuredProducts ?? collect())->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                    @foreach($featuredProducts as $promoProduct)
                        @php
                            $product = $promoProduct->product;
                        @endphp
                        @continue(!$product)
                        <article class="promo-glass-card bg-white/88 border-white/80 overflow-hidden">
                            <div class="h-44 bg-gradient-to-br from-primary/20 to-secondary/10 relative overflow-hidden flex items-center justify-center">
                                <div class="absolute top-3 left-3 z-10">
                                    @if($promoProduct->start_date->isFuture())
                                        <span class="bg-tertiary text-on-tertiary text-[10px] font-bold px-3 py-1 rounded-full uppercase">Mulai {{ $promoProduct->start_date->format('d M') }}</span>
                                    @else
                                        <span class="bg-secondary text-on-secondary text-[10px] font-bold px-3 py-1 rounded-full uppercase">Aktif</span>
                                    @endif
                                </div>
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover"/>
                                @else
                                    <span class="material-symbols-outlined text-8xl text-primary/20" style="font-variation-settings: 'FILL' 1;">inventory_2</span>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-primary font-bold uppercase tracking-wider mb-2">{{ $product->category?->name ?? 'Produk' }}</p>
                                <h3 class="text-lg font-headline font-bold mb-2 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-sm text-on-surface-variant line-clamp-2">{{ $product->description ?: 'Produk terbaru yang sudah ditambahkan di admin.' }}</p>

                                @php
                                    $hargaNormal = $product->price;
                                    $nilaiPotongan = $promoProduct->type === 'percent'
                                        ? ($hargaNormal * ((float) $promoProduct->discount_value / 100))
                                        : (float) $promoProduct->discount_value;
                                    $hargaDiskon = max($product->purchase_price, $hargaNormal - $nilaiPotongan);
                                @endphp

                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-medium text-on-surface-variant/60 line-through">Rp {{ number_format($hargaNormal, 0, ',', '.') }}</span>
                                        <span class="text-xl font-extrabold text-error">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ $product->category ? route('categories.show', $product->category->slug) : route('categories.index') }}" class="landing-btn-neutral p-2.5 rounded-xl">
                                        <span class="material-symbols-outlined">arrow_outward</span>
                                    </a>
                                </div>
                                <p class="mt-2 text-xs text-on-surface-variant">Stok: {{ $product->stock }} {{ $product->unit }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-10 text-center text-on-surface-variant">
                    Belum ada produk aktif saat ini. Produk dari admin akan tampil di sini.
                </div>
            @endif
        </div>
    </section>

    <!-- Lokasi Kami -->
    <section id="location-info" class="px-5 md:px-10 py-16">
        @php
            $locationPhotos = [
                $publicSettings['landing_location_photo_1'] ?? null,
                $publicSettings['landing_location_photo_2'] ?? null,
                $publicSettings['landing_location_photo_3'] ?? null,
            ];
            $filledLocationPhotos = array_values(array_filter($locationPhotos));
        @endphp
        <div class="mb-6 md:mb-8">
            <h2 class="text-3xl md:text-4xl font-headline font-extrabold text-on-surface">Lokasi Kami</h2>
            <p class="text-on-surface-variant font-medium mt-2">Temukan lokasi kami dan kunjungi kapan saja.</p>
        </div>
        <div class="rounded-3xl border border-white/30 bg-white/10 p-5 md:p-7 shadow-xl" style="backdrop-filter: blur(10px);">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="rounded-[20px] overflow-hidden bg-surface-container-low border border-white/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl"
                         style="box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);">
                        <iframe
                            title="Lokasi Ils mart"
                            src="https://www.openstreetmap.org/export/embed.html?bbox=106.8025%2C-6.2200%2C106.8235%2C-6.2020&layer=mapnik&marker=-6.2113%2C106.8130"
                            class="w-full h-[320px] md:h-[380px]"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center gap-2 bg-white/40 px-4 py-2 rounded-full border border-white/40 text-sm text-on-surface">
                            <span class="material-symbols-outlined text-primary">location_on</span>
                            {{ $publicSettings['store_address'] }}
                        </span>
                        <a href="https://www.openstreetmap.org/?mlat=-6.2113&mlon=106.8130#map=16/-6.2113/106.8130" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-sm font-semibold text-primary bg-white/40 hover:bg-primary hover:text-on-primary transition-all">
                            Buka Peta
                            <span class="material-symbols-outlined text-base">open_in_new</span>
                        </a>
                    </div>
                </div>

                <div class="rounded-[20px] p-5 md:p-6 border border-white/20 bg-surface-container-low/60">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl md:text-2xl font-headline font-extrabold text-on-surface">Galeri Toko</h3>
                
                    </div>

                    <div id="location-gallery" class="relative">
                        <div class="rounded-2xl overflow-hidden border border-outline-variant/30 bg-surface-container-lowest h-56 md:h-72">
                            <div id="location-gallery-track" class="flex h-full transition-transform duration-700 ease-out transition-opacity duration-300 opacity-100">
                                @forelse($filledLocationPhotos as $index => $photo)
                                    <div class="w-full h-full flex-none">
                                        <img src="{{ asset('storage/'.$photo) }}" alt="Foto Toko {{ $index + 1 }}" class="w-full h-full object-cover"/>
                                    </div>
                                @empty
                                    <div class="w-full h-full flex-none">
                                        <div class="w-full h-full flex flex-col items-center justify-center text-on-surface-variant gap-2">
                                            <span class="material-symbols-outlined text-4xl">storefront</span>
                                            <p class="text-sm font-medium">Foto toko belum diatur</p>
                                            <p class="text-xs">Silakan unggah foto dari admin pengaturan.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        @if(count($filledLocationPhotos) > 1)
                            <button type="button" id="location-gallery-prev" aria-label="Foto Sebelumnya" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/45 text-white inline-flex items-center justify-center hover:bg-black/60 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                            </button>
                            <button type="button" id="location-gallery-next" aria-label="Foto Selanjutnya" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/45 text-white inline-flex items-center justify-center hover:bg-black/60 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                            </button>

                            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2">
                                @foreach($filledLocationPhotos as $index => $photo)
                                    <button type="button" data-slide-to="{{ $index }}" class="w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white transition-colors" aria-label="Ke foto {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer class="bg-surface-container-low pt-16 pb-10 px-6 md:px-10 mt-8">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10 mb-12 items-stretch">
        <div class="h-full flex flex-col">
            <span class="text-3xl font-black text-primary font-headline tracking-tighter mb-6 block">{{ $publicSettings['store_name'] }}</span>
            <p class="text-on-surface-variant text-sm font-medium leading-relaxed">
                {{ $publicSettings['landing_about_desc'] ?? 'Solusi belanja retail terlengkap dan modern. Kualitas terbaik dari berbagai kategori kebutuhan hidup Anda dalam satu atap digital.' }}
            </p>
        </div>
        <div class="h-full flex flex-col">
            <h4 class="font-headline font-extrabold text-on-surface mb-6 uppercase tracking-widest text-xs">KATEGORI UTAMA</h4>
            <ul class="space-y-3 text-sm font-medium text-on-surface-variant flex-1">
                @foreach($categories->take(5) as $cat)
                    <li><a class="hover:text-primary transition-colors" href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="h-full flex flex-col">
            <h4 class="font-headline font-extrabold text-on-surface mb-6 uppercase tracking-widest text-xs">KONTAK KAMI</h4>
            <div class="space-y-4 text-sm font-medium text-on-surface-variant flex-1">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-base text-primary">schedule</span>
                    <div>
                        <p class="font-bold text-on-surface">Jam Operasional</p>
                        <p>Setiap Hari: 07:00 - 22:00</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-base text-primary">call</span>
                    <div>
                        <p class="font-bold text-on-surface">Telepon</p>
                        <p>{{ $publicSettings['store_phone'] }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-base text-primary">mail</span>
                    <div>
                        <p class="font-bold text-on-surface">Email Support</p>
                        <p>{{ $publicSettings['store_email'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border-t border-outline-variant/20 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-xs font-label uppercase tracking-widest text-on-surface-variant">© {{ date('Y') }} {{ strtoupper($publicSettings['store_name']) }}. {{ strtoupper($publicSettings['footer_text']) }}</p>
        <div class="flex gap-8">
            <a class="text-xs font-label uppercase tracking-widest text-on-surface-variant hover:text-primary" href="#">Instagram</a>
            <a class="text-xs font-label uppercase tracking-widest text-on-surface-variant hover:text-primary" href="#">Twitter</a>
            <a class="text-xs font-label uppercase tracking-widest text-on-surface-variant hover:text-primary" href="#">Facebook</a>
        </div>
    </div>
</footer>

<script>
    (function () {
        const filterButtons = document.querySelectorAll('[data-category-filter]');
        const productGrid = document.getElementById('landing-products-grid');
        const productCards = productGrid ? Array.from(productGrid.querySelectorAll('[data-product-card]')) : [];
        const productsCountEl = document.getElementById('landing-products-count');
        const emptyEl = document.getElementById('landing-products-empty');

        function updateCount(visibleCount) {
            if (!productsCountEl) return;
            productsCountEl.textContent = `Menampilkan ${visibleCount} produk`;
        }

        function showEmptyIfNeeded() {
            if (!emptyEl) return;
            const anyVisible = productCards.some(c => !c.classList.contains('hidden'));
            emptyEl.classList.toggle('hidden', anyVisible);
        }

        function applyFilter(slug) {
            let visible = 0;
            productCards.forEach(card => {
                const cat = card.getAttribute('data-product-category') || 'all';
                if (slug === 'all' || cat === slug) {
                    card.classList.remove('hidden');
                    visible++;
                } else {
                    card.classList.add('hidden');
                }
            });
            updateCount(visible);
            showEmptyIfNeeded();
        }

        if (filterButtons.length && productCards.length) {
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    const slug = this.getAttribute('data-category-filter');
                    // toggle active pill
                    filterButtons.forEach(b => b.classList.remove('nav-link-pill-active'));
                    this.classList.add('nav-link-pill-active');
                    applyFilter(slug);
                });
            });

            // initial state: show all
            applyFilter('all');
        }
    })();
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const initCarousel = ({ galleryId, trackId, prevId, nextId, intervalMs = 3500 }) => {
        const gallery = document.getElementById(galleryId);
        const track = document.getElementById(trackId);
        if (!gallery || !track) return;

        const slides = Array.from(track.children);
        if (slides.length <= 1) return;

        const prevBtn = document.getElementById(prevId);
        const nextBtn = document.getElementById(nextId);
        const dots = Array.from(gallery.querySelectorAll('[data-slide-to]'));

        let currentIndex = 0;
        let autoSlideId;
        let touchStartX = null;

        const updateSlide = (useFade = true) => {
            if (useFade) {
                track.classList.remove('opacity-100');
                track.classList.add('opacity-90');
            }

            track.style.transform = `translateX(-${currentIndex * 100}%)`;

            if (useFade) {
                setTimeout(() => {
                    track.classList.remove('opacity-90');
                    track.classList.add('opacity-100');
                }, 120);
            }

            dots.forEach((dot, index) => {
                dot.classList.toggle('bg-white', index === currentIndex);
                dot.classList.toggle('bg-white/50', index !== currentIndex);
            });
        };

        const goNext = () => {
            currentIndex = (currentIndex + 1) % slides.length;
            updateSlide();
        };

        const goPrev = () => {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            updateSlide();
        };

        const startAutoSlide = () => {
            clearInterval(autoSlideId);
            autoSlideId = setInterval(goNext, intervalMs);
        };

        prevBtn?.addEventListener('click', function () {
            goPrev();
            startAutoSlide();
        });

        nextBtn?.addEventListener('click', function () {
            goNext();
            startAutoSlide();
        });

        dots.forEach((dot, index) => {
            dot.addEventListener('click', function () {
                currentIndex = index;
                updateSlide();
                startAutoSlide();
            });
        });

        gallery.addEventListener('mouseenter', function () {
            clearInterval(autoSlideId);
        });

        gallery.addEventListener('mouseleave', function () {
            startAutoSlide();
        });

        gallery.addEventListener('touchstart', function (event) {
            touchStartX = event.changedTouches[0].clientX;
        }, { passive: true });

        gallery.addEventListener('touchend', function (event) {
            if (touchStartX === null) return;

            const touchEndX = event.changedTouches[0].clientX;
            const diff = touchEndX - touchStartX;

            if (Math.abs(diff) > 40) {
                if (diff < 0) {
                    goNext();
                } else {
                    goPrev();
                }
                startAutoSlide();
            }

            touchStartX = null;
        }, { passive: true });

        updateSlide(false);
        startAutoSlide();
    };

    const initCategoryProductFilter = () => {
        const filterButtons = Array.from(document.querySelectorAll('[data-category-filter]'));
        const productCards = Array.from(document.querySelectorAll('[data-product-card]'));
        const countEl = document.getElementById('landing-products-count');
        const emptyEl = document.getElementById('landing-products-empty');

        if (filterButtons.length === 0 || productCards.length === 0) {
            return;
        }

        const applyFilter = (slug) => {
            let visibleCount = 0;

            productCards.forEach((card) => {
                const matches = slug === 'all' || card.dataset.productCategory === slug;
                card.classList.toggle('hidden', !matches);
                if (matches) {
                    visibleCount += 1;
                }
            });

            filterButtons.forEach((button) => {
                const isActive = button.dataset.categoryFilter === slug;
                button.classList.toggle('nav-link-pill-active', isActive);
                button.classList.toggle('bg-white', !isActive);
                button.classList.toggle('border', !isActive);
                button.classList.toggle('border-white/75', !isActive);
            });

            if (countEl) {
                countEl.textContent = `Menampilkan ${visibleCount} produk`;
            }

            if (emptyEl) {
                emptyEl.classList.toggle('hidden', visibleCount > 0);
            }
        };

        filterButtons.forEach((button) => {
            button.addEventListener('click', function () {
                applyFilter(button.dataset.categoryFilter || 'all');
            });
        });

        applyFilter('all');
    };

    initCarousel({
        galleryId: 'hero-promo-gallery',
        trackId: 'hero-promo-gallery-track',
        prevId: 'hero-promo-gallery-prev',
        nextId: 'hero-promo-gallery-next',
        intervalMs: 3200,
    });

    initCarousel({
        galleryId: 'location-gallery',
        trackId: 'location-gallery-track',
        prevId: 'location-gallery-prev',
        nextId: 'location-gallery-next',
        intervalMs: 3500,
    });

    initCategoryProductFilter();
});
</script>

</body>
</html>
