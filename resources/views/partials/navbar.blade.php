@php
    $active = $active ?? 'home';
    $showSearch = $showSearch ?? false;
    $searchAction = $searchAction ?? route('categories.index');
    $searchPlaceholder = $searchPlaceholder ?? 'Cari produk atau kategori...';
    $authVariant = $authVariant ?? 'logout'; // logout | dashboard
    $storeName = $publicSettings['store_name'] ?? 'Nexus Retail';
@endphp

<nav class="sticky top-0 z-50 sticky-blur-nav border-b border-outline-variant/20">
    <div class="px-5 md:px-10 py-3.5">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                <span class="text-xl md:text-2xl font-black text-primary font-headline tracking-tight">{{ $storeName }}</span>
            </a>

            <div class="hidden lg:flex items-center gap-1 font-headline tracking-tight bg-white/75 px-2 py-1 rounded-full border border-white/70 shadow-sm">
                <a class="{{ $active === 'home' ? 'nav-link-pill nav-link-pill-active' : 'nav-link-pill bg-white/70 border border-white/70' }}" href="{{ route('home') }}">Beranda</a>
                <a class="{{ $active === 'categories' ? 'nav-link-pill nav-link-pill-active' : 'nav-link-pill bg-white/70 border border-white/70' }}" href="{{ route('categories.index') }}">Kategori</a>
                <a class="{{ $active === 'promos' ? 'nav-link-pill nav-link-pill-active' : 'nav-link-pill bg-white/70 border border-white/70' }}" href="{{ route('promos.index') }}">Promo</a>
            </div>

            <div class="flex items-center gap-2">
                @if($showSearch)
                    <form action="{{ $searchAction }}" method="GET" class="hidden xl:block">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                            <input type="text" name="search" placeholder="{{ $searchPlaceholder }}"
                                   class="w-64 rounded-full border border-outline-variant/30 bg-white/80 pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary"/>
                        </div>
                    </form>
                @endif

                @guest
                    <a href="{{ route('login') }}" class="landing-btn btn-inline text-sm py-2.5 px-5">Masuk</a>
                @else
                    @if($authVariant === 'dashboard')
                        <a href="{{ route('admin.dashboard') }}" class="landing-btn btn-inline text-sm py-2.5 px-5">Dashboard</a>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="landing-btn-neutral btn-inline">Keluar</button>
                        </form>
                    @endif
                @endguest
            </div>
        </div>

        <div class="mt-3 flex lg:hidden items-center gap-2 overflow-x-auto category-scroll pb-1">
            <a class="{{ $active === 'home' ? 'nav-link-pill nav-link-pill-active' : 'nav-link-pill bg-white/75 border border-white/70' }}" href="{{ route('home') }}">Beranda</a>
            <a class="{{ $active === 'categories' ? 'nav-link-pill nav-link-pill-active' : 'nav-link-pill bg-white/75 border border-white/70' }}" href="{{ route('categories.index') }}">Kategori</a>
            <a class="{{ $active === 'promos' ? 'nav-link-pill nav-link-pill-active' : 'nav-link-pill bg-white/75 border border-white/70' }}" href="{{ route('promos.index') }}">Promo</a>

            @if($showSearch)
                <form action="{{ $searchAction }}" method="GET" class="ml-auto min-w-[200px] flex-1 max-w-[280px]">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                        <input type="text" name="search" placeholder="Cari..."
                               class="w-full rounded-full border border-outline-variant/30 bg-white/80 pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary"/>
                    </div>
                </form>
            @endif
        </div>
    </div>
</nav>
