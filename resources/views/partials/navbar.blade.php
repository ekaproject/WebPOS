@php
    $active = $active ?? 'home';
    $showSearch = $showSearch ?? false;
    $searchAction = $searchAction ?? route('categories.index');
    $searchPlaceholder = $searchPlaceholder ?? 'Cari produk atau kategori...';
    $authVariant = $authVariant ?? 'logout'; // logout | dashboard
    $storeName = $publicSettings['store_name'] ?? 'Nexus Retail';
@endphp

<nav class="fixed top-0 w-full flex justify-between items-center px-5 md:px-10 py-3.5 glass-nav z-50 border-b border-outline-variant/15 backdrop-blur-xl">
    <a href="{{ route('home') }}" class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-2xl" style="font-variation-settings: 'FILL' 1;">grid_view</span>
        <span class="text-2xl font-black text-primary font-headline tracking-tight">{{ $storeName }}</span>
    </a>

    <div class="hidden md:flex items-center gap-1 font-headline font-semibold tracking-tight bg-surface-container-low/90 px-2 py-1 rounded-full border border-outline-variant/25 shadow-sm">
        <a class="{{ $active === 'home' ? 'text-primary bg-primary/10' : 'text-on-surface-variant hover:text-primary hover:bg-primary/10' }} px-4 py-2 rounded-full transition-all" href="{{ route('home') }}">Beranda</a>
        <a class="{{ $active === 'categories' ? 'text-primary bg-primary/10' : 'text-on-surface-variant hover:text-primary hover:bg-primary/10' }} px-4 py-2 rounded-full transition-all" href="{{ route('categories.index') }}">Kategori</a>
        <a class="{{ $active === 'promos' ? 'text-primary bg-primary/10' : 'text-on-surface-variant hover:text-primary hover:bg-primary/10' }} px-4 py-2 rounded-full transition-all" href="{{ route('promos.index') }}">Promo</a>
    </div>

    <div class="flex items-center gap-3">
        @if($showSearch)
            <form action="{{ $searchAction }}" method="GET" class="hidden md:block">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                    <input type="text" name="search" placeholder="{{ $searchPlaceholder }}"
                           class="w-64 rounded-full border border-outline-variant/30 bg-surface-container-low pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary focus:border-primary"/>
                </div>
            </form>
        @endif

        @guest
            <a href="{{ route('login') }}"
               class="px-6 py-2.5 rounded-full font-headline font-bold text-sm text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all ring-2 ring-[#0369A1]/20"
               style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
                Masuk
            </a>
        @else
            @if($authVariant === 'dashboard')
                <a href="{{ route('admin.dashboard') }}"
                   class="px-6 py-2.5 rounded-full font-headline font-bold text-sm text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all ring-2 ring-[#0369A1]/20"
                   style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 100%);">
                    Dashboard
                </a>
            @else
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="px-6 py-2.5 rounded-full font-headline font-bold text-sm bg-surface-container-high text-on-surface border border-outline-variant/30 hover:bg-surface-container-highest transition-all">
                        Keluar
                    </button>
                </form>
            @endif
        @endguest
    </div>
</nav>
