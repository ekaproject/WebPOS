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
<body class="bg-surface font-body text-on-surface">

<!-- Navigation -->
@include('partials.navbar', [
    'active' => 'categories',
    'authVariant' => 'dashboard',
])

<main class="pt-20">

    <!-- Category Header -->
    <section class="px-6 md:px-10 py-10 bg-surface-container-low">
        <div class="flex items-center gap-3 text-sm text-on-surface-variant mb-6">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-base">chevron_right</span>
            <span class="text-primary font-bold">{{ $category->name }}</span>
        </div>
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center flex-none">
                <span class="material-symbols-outlined text-3xl text-primary">{{ $category->icon }}</span>
            </div>
            <div>
                <h1 class="text-4xl font-headline font-extrabold tracking-tight text-primary">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-on-surface-variant mt-1">{{ $category->description }}</p>
                @endif
            </div>
        </div>
    </section>

    <!-- Category Filter Bar -->
    <section class="px-6 md:px-10 py-4 bg-surface-container-lowest border-b border-outline-variant/10 sticky top-16 z-30">
        <div class="flex gap-3 overflow-x-auto category-scroll">
            <a href="{{ route('categories.index') }}"
               class="flex-none px-5 py-2 rounded-full bg-surface-container text-on-surface-variant font-bold text-sm hover:bg-[#0ea5e9]/15 hover:text-[#0ea5e9] transition-all whitespace-nowrap">
                Semua Kategori
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="flex-none px-5 py-2 rounded-full font-bold text-sm transition-all whitespace-nowrap flex items-center gap-2
                          {{ $cat->slug === $category->slug ? 'bg-[#0ea5e9] text-white shadow-md' : 'bg-surface-container text-on-surface-variant hover:bg-[#0ea5e9]/15 hover:text-[#0ea5e9]' }}">
                    <span class="material-symbols-outlined text-base">{{ $cat->icon }}</span>
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </section>

    <!-- Search & Products -->
    <section class="px-6 md:px-10 py-10">

        <!-- Search Bar -->
        <form method="GET" action="{{ route('categories.show', $category->slug) }}" class="mb-8 flex gap-3 max-w-lg">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari produk di {{ $category->name }}..."
                       class="w-full pl-10 pr-4 py-3 rounded-xl border-outline-variant/40 bg-surface-container-lowest text-sm focus:ring-2 focus:ring-primary"/>
            </div>
            <button type="submit" class="px-6 py-3 rounded-xl text-white font-bold text-sm"
                    style="background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%);">
                Cari
            </button>
            @if(request('search'))
                <a href="{{ route('categories.show', $category->slug) }}" class="px-6 py-3 rounded-xl bg-surface-container text-on-surface-variant font-bold text-sm hover:bg-[#0ea5e9]/10 hover:text-[#0ea5e9] transition-all">
                    Reset
                </a>
            @endif
        </form>

        <!-- Product Count -->
        <p class="text-sm text-on-surface-variant mb-6">
            Menampilkan <strong>{{ $products->total() }}</strong> produk
            @if(request('search')) untuk "<strong>{{ request('search') }}</strong>" @endif
        </p>

        <!-- Product Grid -->
        @if($products->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                @foreach($products as $product)
                <div class="bg-surface-container-lowest rounded-2xl overflow-hidden group transition-all hover:shadow-xl border border-outline-variant/10">
                    <div class="h-40 bg-gradient-to-br from-primary/10 to-primary/5 relative overflow-hidden flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                                 class="w-full h-full object-cover"/>
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
                    <div class="p-4">
                        <p class="text-xs text-secondary font-bold uppercase tracking-wider mb-1">{{ $product->category->name }}</p>
                        <h3 class="font-headline font-bold text-sm leading-tight mb-3 line-clamp-2">{{ $product->name }}</h3>
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <span class="text-lg font-extrabold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <p class="text-xs text-on-surface-variant">/ {{ $product->unit }}</p>
                            </div>
                            <a href="{{ route('categories.show', $category->slug) }}" class="bg-primary/10 text-primary p-2 rounded-xl hover:bg-primary hover:text-on-primary transition-colors flex-none inline-flex">
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
            <div class="text-center py-24 text-on-surface-variant">
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
