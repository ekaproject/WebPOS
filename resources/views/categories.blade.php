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
<body class="bg-surface font-body text-on-surface">

<!-- Navigation -->
@include('partials.navbar', [
    'active' => 'categories',
    'authVariant' => 'dashboard',
])

<main class="pt-20">

    <!-- Header -->
    <section class="px-6 md:px-10 py-12 bg-surface-container-low">
        <div class="flex items-center gap-3 text-sm text-on-surface-variant mb-4">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-base">chevron_right</span>
            <span class="text-primary font-bold">Kategori Belanja</span>
        </div>
        <h1 class="text-4xl font-headline font-extrabold tracking-tight text-primary">Kategori Belanja</h1>
        <p class="text-on-surface-variant mt-2">Temukan produk yang Anda butuhkan berdasarkan kategori</p>

        <form action="{{ route('categories.index') }}" method="GET" class="mt-6">
            <div class="relative max-w-xl">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori, produk, atau SKU..."
                       class="w-full rounded-2xl border border-outline-variant/30 bg-surface-container-lowest pl-10 pr-28 py-3 text-sm focus:ring-2 focus:ring-primary focus:border-primary"/>
                <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-4 py-2 rounded-xl text-white text-xs font-bold hover:brightness-110 transition-all"
                        style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
                    Cari
                </button>
            </div>
        </form>

        @if(request('search'))
            <p class="mt-3 text-sm text-on-surface-variant">
                Hasil filter untuk: <span class="font-bold text-primary">{{ request('search') }}</span>
            </p>
        @endif
    </section>

    <!-- Category Grid -->
    <section class="px-6 md:px-10 py-12">
        @if($categories->count())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                       class="bg-surface-container-lowest rounded-2xl p-6 text-center hover:bg-[#0284C7]/10 hover:shadow-lg transition-all group border border-outline-variant/10">
                        <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                            <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">{{ $category->icon }}</span>
                        </div>
                        <h3 class="font-headline font-bold text-sm leading-tight mb-1">{{ $category->name }}</h3>
                        @if($category->description)
                            <p class="text-[11px] text-on-surface-variant mt-1">{{ $category->description }}</p>
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
    </section>

</main>

<!-- Footer -->
<footer class="bg-inverse-surface text-inverse-on-surface mt-20 px-6 md:px-10 py-8 text-center text-sm">
    <p>&copy; {{ date('Y') }} {{ $publicSettings['store_name'] }}. {{ $publicSettings['footer_text'] }}</p>
</footer>

</body>
</html>
