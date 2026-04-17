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
                    <div class="relative flex-1">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori, produk, atau SKU..."
                               class="w-full rounded-2xl border border-white/45 bg-white/90 pl-10 pr-4 py-3 text-sm text-on-surface focus:ring-2 focus:ring-primary focus:border-primary"/>
                    </div>
                    <button type="submit" class="landing-btn btn-inline sm:min-w-[120px]">Cari</button>
                </div>
            </form>

            @if(request('search'))
                <p class="mt-3 text-sm text-white/80">
                    Hasil filter untuk: <span class="font-bold text-white">{{ request('search') }}</span>
                </p>
            @endif
        </div>
    </section>

    <!-- Category Grid -->
    <section class="px-6 md:px-10 pb-14">
        <div class="rounded-[1.8rem] border border-white/70 bg-white/72 backdrop-blur-sm p-6 md:p-8 shadow-[0_14px_32px_rgba(2,54,97,0.08)]">
            @if($categories->count())
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
                    <span class="material-symbols-outlined text-6xl block mb-3">category</span>
                    <p class="font-bold text-lg">Belum ada kategori tersedia.</p>
                </div>
            @endif
        </div>
    </section>

</main>

<!-- Footer -->
<footer class="bg-inverse-surface text-inverse-on-surface mt-20 px-6 md:px-10 py-8 text-center text-sm">
    <p>&copy; {{ date('Y') }} {{ $publicSettings['store_name'] }}. {{ $publicSettings['footer_text'] }}</p>
</footer>

</body>
</html>
