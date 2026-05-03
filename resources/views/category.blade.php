<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ $category->name }} | {{ $publicSettings['store_name'] }}</title>
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

    <!-- Category Header -->
    <section class="px-6 md:px-10 pt-4 md:pt-6 pb-8">
        <div class="page-hero-gradient p-6 md:p-8 lg:p-10">
            <div class="flex items-center gap-3 text-sm text-white/80 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-base">chevron_right</span>
                <span class="text-white font-bold">{{ $category->name }}</span>
            </div>
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-white/85 flex items-center justify-center flex-none">
                    <span class="material-symbols-outlined text-3xl text-primary">{{ $category->icon }}</span>
                </div>
                <div>
                    <h1 class="text-4xl md:text-5xl font-headline font-extrabold tracking-tight text-white">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="text-white/85 mt-1">{{ $category->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Category Filter Bar -->
    <section class="px-6 md:px-10 py-4 sticky top-[78px] md:top-[86px] z-30">
        <div class="rounded-2xl border border-white/70 bg-white/80 backdrop-blur-sm p-2 shadow-[0_10px_24px_rgba(2,54,97,0.12)]">
            <div class="flex gap-2 overflow-x-auto category-scroll">
                <a href="{{ route('categories.index') }}" class="nav-link-pill bg-white border border-white/70 flex-none whitespace-nowrap">Semua Kategori</a>
                @foreach($categories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}"
                       class="flex-none whitespace-nowrap flex items-center gap-2 {{ $cat->slug === $category->slug ? 'nav-link-pill nav-link-pill-active' : 'nav-link-pill bg-white border border-white/70' }}">
                        <span class="material-symbols-outlined text-base">{{ $cat->icon }}</span>
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Search & Products -->
    <section class="px-6 md:px-10 py-8 md:py-10">

        <!-- Search Bar -->
        <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="mb-8 max-w-2xl">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari produk di {{ $category->name }}..."
                           class="w-full pl-10 pr-4 py-3 rounded-2xl border border-white/75 bg-white/85 text-sm focus:ring-2 focus:ring-primary focus:border-primary"/>
                </div>
                <button type="submit" class="landing-btn btn-inline sm:min-w-[120px]">Cari</button>
                @if(request('search'))
                    <a href="{{ route('categories.show', $category->slug) }}" class="landing-btn-neutral btn-inline">Reset</a>
                @endif
            </div>
        </form>

        <!-- Product Count -->
        <p class="text-sm text-on-surface-variant mb-6">
            Menampilkan <strong>{{ $products->total() }}</strong> produk
            @if(request('search')) untuk "<strong>{{ request('search') }}</strong>" @endif
        </p>

        <!-- Product Grid -->
        @if($products->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                @foreach($products as $product)
                <div class="promo-glass-card bg-white/90 border-white/80 overflow-hidden group">
                    <div class="h-48 bg-gradient-to-br from-primary/10 to-primary/5 relative overflow-hidden flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
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
                            <a href="{{ route('categories.show', $category->slug) }}" class="landing-btn-neutral p-2 rounded-xl flex-none inline-flex">
                                <span class="material-symbols-outlined text-base">arrow_outward</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="mt-10">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif

        @else
            <div class="text-center py-24 text-on-surface-variant rounded-3xl border border-white/70 bg-white/72 backdrop-blur-sm">
                <span class="material-symbols-outlined text-6xl block mb-3">search_off</span>
                <p class="font-bold text-lg mb-1">Produk tidak ditemukan</p>
                <p class="text-sm">Coba kata kunci lain atau lihat kategori lainnya.</p>
                <a href="{{ route('categories.show', $category->slug) }}" class="inline-block mt-4 text-primary font-bold hover:underline">Lihat semua produk {{ $category->name }}</a>
            </div>
        @endif
    </section>
</main>

<!-- Footer -->
<footer class="bg-inverse-surface text-inverse-on-surface mt-20 px-6 md:px-10 py-8 text-center text-sm">
    <p>&copy; {{ date('Y') }} {{ $publicSettings['store_name'] }}. {{ $publicSettings['footer_text'] }}</p>
</footer>

</body>
</html>
