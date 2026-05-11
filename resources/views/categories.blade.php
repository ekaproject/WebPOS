<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kategori Belanja | {{ $publicSettings['store_name'] }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @include('partials.vite-assets')
</head>
<body class="landing-surface font-body text-on-surface">

<!-- Navigation -->
@include('partials.navbar', [
    'active' => 'categories',
    'authVariant' => 'dashboard',
    'hideAuthLink' => true,
])

<main class="pt-6 md:pt-8">

    <!-- Header -->
    <section class="px-6 md:px-10 pt-4 md:pt-6 pb-10">
        <div class="page-hero-gradient p-6 md:p-8 lg:p-10">
            <div class="flex items-center gap-3 text-sm text-white/80 mb-4">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-base">chevron_right</span>
                <span class="text-white font-bold">Kategori Belanja</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tight text-white">Kategori Belanja</h1>
            <p class="text-white/85 mt-2 max-w-2xl">Temukan produk yang Anda butuhkan berdasarkan kategori, dari kebutuhan harian sampai produk favorit keluarga.</p>

            <form action="{{ route('categories.index') }}" method="GET" class="mt-6 w-full max-w-2xl">
    <div class="flex flex-col sm:flex-row gap-3">
        
        <div class="flex items-center flex-1 rounded-2xl border border-white/45 bg-white/90 px-4">
            
            <span class="material-symbols-outlined text-on-surface-variant text-lg mr-2">
                search
            </span>

            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari nama produk..."
                class="flex-1 py-3 text-sm text-on-surface bg-transparent focus:outline-none"
            />
            
        </div>

        <button type="submit" class="landing-btn btn-inline sm:min-w-[120px]">
            Cari
        </button>
    </div>
</form>

            @if(request('search'))
                <p class="mt-3 text-sm text-white/80">
                    Hasil filter untuk: <span class="font-bold text-white">{{ request('search') }}</span>
                </p>
            @endif
        </div>
    </section>

    <!-- Product Search Results -->
    @if(request('search') && isset($searchProducts) && $searchProducts->count())
        <section class="px-6 md:px-10 pb-8">
            <div class="rounded-[1.8rem] border border-white/70 bg-white/72 backdrop-blur-sm p-6 md:p-8 shadow-[0_14px_32px_rgba(2,54,97,0.08)]">
                <p class="text-sm text-on-surface-variant mb-5">
                    Ditemukan <strong>{{ $searchProducts->count() }}</strong> produk untuk "<strong>{{ request('search') }}</strong>"
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                    @foreach($searchProducts as $product)
                    <div class="promo-glass-card bg-white/90 border-white/80 overflow-hidden group">
                        <div class="h-48 bg-gradient-to-br from-primary/10 to-primary/5 relative overflow-hidden flex items-center justify-center">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                     class="w-full h-full object-contain object-center"/>
                            @else
                                <span class="material-symbols-outlined text-6xl text-primary/20"
                                      style="font-variation-settings: 'FILL' 1;">
                                    {{ $product->category->icon ?? 'inventory_2' }}
                                </span>
                            @endif
                            @if($product->isLowStock())
                                <div class="absolute top-2 left-2">
                                    <span class="bg-error text-on-error text-[10px] font-bold px-2 py-0.5 rounded-full">Stok Terbatas</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="text-[11px] text-secondary font-bold uppercase tracking-wider mb-1">{{ $product->category->name }}</p>
                            <h3 class="font-headline font-bold text-sm leading-tight mb-2 line-clamp-2">{{ $product->name }}</h3>
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <span class="text-base font-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    <p class="text-xs text-on-surface-variant">/ {{ $product->unit }}</p>
                                </div>
                                <a href="{{ route('categories.show', $product->category->slug) }}" class="landing-btn-neutral p-2 rounded-xl flex-none inline-flex">
                                    <span class="material-symbols-outlined text-base">arrow_outward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- Category Grid -->
    @if(!request('search') || (isset($searchProducts) && $searchProducts->isEmpty()))
    <section class="px-6 md:px-10 pb-14">
        <div class="rounded-[1.8rem] border border-white/70 bg-white/72 backdrop-blur-sm p-6 md:p-8 shadow-[0_14px_32px_rgba(2,54,97,0.08)]">
            @if($categories->count())
                @if(request('search'))
                    <p class="text-sm text-on-surface-variant mb-5">
                        Tidak ditemukan produk untuk "<strong>{{ request('search') }}</strong>". Menampilkan kategori terkait:
                    </p>
                @endif
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-5">
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="promo-glass-card bg-white/88 border-white/80 p-5 text-center group">
                            <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                                <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">{{ $category->icon }}</span>
                            </div>
                            <h3 class="font-headline font-bold text-sm leading-tight mb-1">{{ $category->name }}</h3>
                            @if($category->description)
                                <p class="text-[11px] text-on-surface-variant mt-1 line-clamp-2">{{ $category->description }}</p>
                            @endif
                            <p class="text-xs text-[#0284C7] font-bold mt-3">{{ $category->products_count }} Produk</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-24 text-on-surface-variant">
                    <span class="material-symbols-outlined text-6xl block mb-3">search_off</span>
                    <p class="font-bold text-lg">Tidak ditemukan hasil untuk "{{ request('search') }}"</p>
                    <p class="text-sm mt-1">Coba kata kunci lain.</p>
                    <a href="{{ route('categories.index') }}" class="inline-block mt-4 text-primary font-bold hover:underline">Lihat semua kategori</a>
                </div>
            @endif
        </div>
    </section>
    @endif

</main>

<!-- Footer -->
<footer class="bg-inverse-surface text-inverse-on-surface mt-20 px-6 md:px-10 py-8 text-center text-sm">
    <p>&copy; {{ date('Y') }} {{ $publicSettings['store_name'] }}. {{ $publicSettings['footer_text'] }}</p>
</footer>

</body>
</html>
