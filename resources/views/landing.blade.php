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
<body class="bg-surface font-body text-on-surface">

<!-- Top Navigation -->
@include('partials.navbar', [
    'active' => 'home',
    'showSearch' => true,
    'searchAction' => route('categories.index'),
    'searchPlaceholder' => 'Cari produk atau kategori...',
    'authVariant' => 'logout',
])

<main class="pt-20">
    <!-- Hero Section -->
    <section class="px-5 md:px-10 py-8 md:py-10">
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
        @endphp
        <div class="relative w-full min-h-[560px] md:min-h-[620px] overflow-hidden rounded-3xl bg-surface-container">
            <div class="absolute inset-0" style="background: linear-gradient(135deg, #0F4C81 0%, #2563EB 100%);"></div>
            <div class="absolute -top-20 -right-16 w-64 h-64 rounded-full bg-[#0284C7]/25 blur-3xl"></div>
            <div class="absolute -bottom-24 -left-20 w-72 h-72 rounded-full bg-[#22C55E]/25 blur-3xl"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center p-8 md:p-12 lg:p-16">
                <div class="max-w-2xl">
                    <span class="inline-block px-4 py-1.5 rounded-full bg-white/20 text-white font-headline text-xs font-bold mb-6 tracking-widest uppercase border border-white/30 backdrop-blur-sm shadow-sm">
                        {{ \App\Models\AppSetting::getValue('landing_solusi_text', 'Solusi Belanja Terlengkap') }}
                    </span>
                    <h1 class="text-4xl md:text-6xl lg:text-[72px] font-headline font-extrabold text-white leading-[1.1] tracking-tighter mb-6">
                        {{ \App\Models\AppSetting::getValue('landing_hero_title', 'Satu Tempat untuk Semua Kebutuhan.') }}
                    </h1>
                    <p class="text-base md:text-lg text-white/90 mb-10 max-w-xl font-medium leading-relaxed">
                        {{ \App\Models\AppSetting::getValue('landing_hero_description', 'Mulai dari bahan makanan segar, perlengkapan rumah tangga, hingga bayar tagihan. Belanja cerdas, hidup lebih berkualitas.') }}
                    </p>
                    <div class="flex flex-wrap items-center gap-4">
                        <a href="#categories" class="text-white px-8 py-4 rounded-full font-headline font-bold text-[15px] inline-flex items-center gap-2 shadow-lg hover:scale-[1.03] hover:-translate-y-0.5 transition-all bg-[#0284C7] hover:bg-[#0369A1]">
                            Jelajahi Kategori Sekarang
                            <span class="material-symbols-outlined">trending_flat</span>
                        </a>
                        <a href="{{ route('promos.index') }}" class="px-8 py-4 rounded-full border border-white/40 bg-white/10 text-white font-bold text-[15px] hover:bg-white/25 hover:border-white/60 transition-all backdrop-blur-sm">
                            Lihat Promo Hari Ini
                        </a>
                    </div>
                </div>

                <div class="w-full lg:max-w-[540px] lg:ml-auto">
                    <div class="rounded-2xl border border-white/20 bg-white/10 backdrop-blur-md p-4 md:p-5 shadow-2xl">
                        <div class="flex items-center justify-between mb-3 px-1">
                            <p class="text-xs uppercase tracking-[0.2em] text-white/80 font-bold">Promo {{ $publicSettings['store_name'] ?? 'ILS Mart' }}</p>
                            <a href="{{ route('promos.index') }}" class="text-[11px] font-bold text-white/90 hover:text-white transition-colors">Lihat Semua</a>
                        </div>

                        <div id="hero-promo-gallery" class="relative">
                            <div class="rounded-2xl overflow-hidden border border-white/20 bg-white/10 h-[380px] md:h-[430px]">
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
                                <button type="button" id="hero-promo-gallery-prev" aria-label="Promo Sebelumnya" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/45 text-white inline-flex items-center justify-center hover:bg-black/60 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                                </button>
                                <button type="button" id="hero-promo-gallery-next" aria-label="Promo Selanjutnya" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-black/45 text-white inline-flex items-center justify-center hover:bg-black/60 transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                </button>

                                <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2">
                                    @foreach($heroPromoSlides as $index => $slide)
                                        <button type="button" data-slide-to="{{ $index }}" class="w-2.5 h-2.5 rounded-full bg-white/50 hover:bg-white transition-colors" aria-label="Ke promo {{ $index + 1 }}"></button>
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
    <section id="categories" class="px-6 md:px-10 py-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-headline font-extrabold tracking-tight text-primary">Kategori Belanja</h2>
            <a href="{{ route('categories.index') }}" class="text-[#0284C7] font-bold flex items-center gap-1 group px-4 py-2 rounded-xl bg-[#0284C7]/15 hover:bg-[#0284C7] hover:text-white transition-all">
                Lihat Semua
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">trending_flat</span>
            </a>
        </div>
        <div class="flex gap-4 overflow-x-auto category-scroll pb-4">
                <a href="{{ route('categories.index') }}"
                   class="flex-none px-6 py-3 rounded-full text-white font-bold shadow-md whitespace-nowrap transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg"
                    style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">Semua</a>
            @foreach($categories->take(8) as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                       class="flex-none px-6 py-3 rounded-full bg-surface-container text-on-surface-variant font-bold hover:bg-[#0284C7]/15 hover:text-[#0284C7] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg whitespace-nowrap flex items-center gap-2">
                    <span class="material-symbols-outlined text-base">{{ $cat->icon }}</span>
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mt-8">
            @foreach($categories->take(6) as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="bg-surface-container-low p-6 rounded-2xl text-center hover:bg-primary/10 hover:shadow-lg transition-all cursor-pointer group">
                    <span class="material-symbols-outlined text-4xl text-primary mb-3 block group-hover:scale-110 transition-transform">{{ $cat->icon }}</span>
                    <h3 class="font-bold text-sm">{{ $cat->name }}</h3>
                    @if($cat->description)
                        <p class="text-[10px] text-on-surface-variant mt-1">{{ $cat->description }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    <section id="promo" class="px-6 md:px-10 py-16 bg-surface-container-low">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-4">
            <div>
                <h2 class="text-4xl font-headline font-extrabold tracking-tight text-primary">Pilihan Terbaik Hari Ini</h2>
                <p class="text-on-surface-variant font-medium mt-2">Menampilkan produk yang sedang promo hari ini.</p>
            </div>
            <a class="text-[#0284C7] font-bold flex items-center gap-1 group px-4 py-2 rounded-xl bg-[#0284C7]/15 hover:bg-[#0284C7] hover:text-white transition-all" href="{{ route('promos.index') }}#promo-products">
                Lihat Semua
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">trending_flat</span>
            </a>
        </div>
        @if(($featuredProducts ?? collect())->isNotEmpty())
            <div class="flex gap-6 overflow-x-auto pb-2">
                @foreach($featuredProducts as $promoProduct)
                    @php
                        $product = $promoProduct->product;
                    @endphp
                    @continue(!$product)
                    <div class="w-[280px] flex-none bg-surface-container-lowest rounded-2xl overflow-hidden group transition-all hover:shadow-xl border border-outline-variant/20">
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
                        <div class="p-6">
                            <p class="text-xs text-primary font-bold uppercase tracking-wider mb-2">{{ $product->category?->name ?? 'Produk' }}</p>
                            <h3 class="text-lg font-headline font-bold mb-2">{{ $product->name }}</h3>
                            <p class="text-sm text-on-surface-variant line-clamp-2">{{ $product->description ?: 'Produk terbaru yang sudah ditambahkan di admin.' }}</p>
                            
                            @php
                                $hargaNormal = $product->price;
                                $hargaDiskon = max($product->purchase_price, $hargaNormal - $promoProduct->discount_value);
                            @endphp

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-medium text-on-surface-variant/60 line-through">Rp {{ number_format($hargaNormal, 0, ',', '.') }}</span>
                                    <span class="text-xl font-extrabold text-error">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ $product->category ? route('categories.show', $product->category->slug) : route('categories.index') }}" class="bg-primary/10 text-primary p-2 rounded-xl hover:bg-primary hover:text-on-primary transition-colors inline-flex">
                                    <span class="material-symbols-outlined">arrow_outward</span>
                                </a>
                            </div>
                            <p class="mt-2 text-xs text-on-surface-variant">Stok: {{ $product->stock }} {{ $product->unit }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 p-10 text-center text-on-surface-variant">
                Belum ada produk aktif saat ini. Produk dari admin akan tampil di sini.
            </div>
        @endif
    </section>

    <!-- Lokasi Kami -->
    <section class="px-5 md:px-10 py-16">
        @php
            $locationPhotos = [
                \App\Models\AppSetting::getValue('landing_location_photo_1'),
                \App\Models\AppSetting::getValue('landing_location_photo_2'),
                \App\Models\AppSetting::getValue('landing_location_photo_3'),
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
                            title="Lokasi Nexus Retail"
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
                        <span class="text-xs font-bold uppercase tracking-widest text-on-surface-variant">Lokasi Kami</span>
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10 mb-12 items-stretch">
        <div class="h-full flex flex-col">
            <span class="text-3xl font-black text-primary font-headline tracking-tighter mb-6 block">{{ $publicSettings['store_name'] }}</span>
            <p class="text-on-surface-variant text-sm font-medium leading-relaxed">
                Solusi belanja retail terlengkap dan modern. Kualitas terbaik dari berbagai kategori kebutuhan hidup Anda dalam satu atap digital.
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
        <div class="h-full flex flex-col">
            <h4 class="font-headline font-extrabold text-on-surface mb-6 uppercase tracking-widest text-xs">BERLANGGANAN</h4>
            <p class="text-sm text-on-surface-variant mb-4">Dapatkan info produk terbaru langsung di email Anda.</p>
            <div class="flex gap-2">
                <input class="bg-surface-container-highest border-none rounded-full px-4 py-2 text-sm w-full focus:ring-2 focus:ring-primary"
                       placeholder="Alamat Email Anda" type="email"/>
                <button class="editorial-gradient text-on-primary p-2 rounded-full">
                    <span class="material-symbols-outlined">send</span>
                </button>
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
});
</script>

</body>
</html>
