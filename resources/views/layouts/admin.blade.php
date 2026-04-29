<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Admin Console') |   ILS Mart</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @include('partials.vite-assets')
    @stack('styles')
</head>
<body class="bg-surface font-body text-on-surface">

<!-- Sidebar -->
<aside class="h-screen w-64 fixed left-0 top-0 bg-surface-container-low admin-sidebar flex flex-col py-6 pr-4 z-50">
    <div class="px-6 mb-8">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">grid_view</span>
            <h1 class="text-lg font-bold text-primary font-headline tracking-tight">ILS Mart</h1>
        </div>
        <p class="text-xs text-on-surface-variant mt-1">Admin Console</p>
    </div>
    <nav class="flex-1 overflow-y-auto space-y-1">
        @if(auth()->user()?->role === 'admin')
            <a href="{{ route('admin.dashboard') }}"
                  class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-medium text-sm">Beranda</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
                  class="{{ request()->routeIs('admin.products*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">shopping_bag</span>
                <span class="font-medium text-sm">Produk</span>
            </a>
            <a href="{{ route('admin.transactions.index') }}"
                  class="{{ request()->routeIs('admin.transactions*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">receipt_long</span>
                <span class="font-medium text-sm">Transaksi</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
                  class="{{ request()->routeIs('admin.categories*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">category</span>
                <span class="font-medium text-sm">Kategori</span>
            </a>
            <a href="{{ route('admin.distributors.index') }}"
                        class="{{ request()->routeIs('admin.distributors*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                    <span class="material-symbols-outlined">local_shipping</span>
                    <span class="font-medium text-sm">Distributor</span>
            </a>
            <a href="{{ route('admin.inbound-items.index') }}"
                        class="{{ request()->routeIs('admin.inbound-items*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                    <span class="material-symbols-outlined">box_add</span>
                    <span class="font-medium text-sm">Barang Masuk &amp; QC</span>
            </a>
            <a href="{{ route('admin.promos.index') }}"
                        class="{{ request()->routeIs('admin.promos*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                    <span class="material-symbols-outlined">local_offer</span>
                    <span class="font-medium text-sm">Promo</span>
            </a>
            <a href="{{ route('admin.returns.index') }}"
                class="{{ request()->routeIs('admin.returns*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">undo</span>
                <span class="font-medium text-sm">Retur Barang</span>
            </a>
            <a href="{{ route('admin.reports.index') }}"
                class="{{ request()->routeIs('admin.reports*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">analytics</span>
                <span class="font-medium text-sm">Laporan</span>
            </a>
            <a href="{{ route('admin.team.index') }}"
                class="{{ request()->routeIs('admin.team*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">group</span>
                <span class="font-medium text-sm">User Management</span>
            </a>
            <a href="{{ route('admin.settings.index') }}"
                class="{{ request()->routeIs('admin.settings*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-medium text-sm">Manajemen Konten</span>
            </a>
        @else
            <a href="{{ route('distributor.returns.index') }}"
                class="{{ request()->routeIs('distributor.returns*') ? 'bg-primary text-on-primary shadow-sm' : 'text-on-surface-variant hover:bg-surface-container-highest hover:translate-x-1' }} rounded-r-full py-3 px-6 flex items-center gap-3 transition-all">
                <span class="material-symbols-outlined">undo</span>
                <span class="font-medium text-sm">Retur Barang</span>
            </a>
        @endif
    </nav>
    <div class="px-4 mt-auto">
        @if(auth()->user()?->role === 'admin')
            <a href="{{ route('admin.inbound-items.create') }}"
               class="w-full bg-primary-container text-on-primary py-3 rounded-xl font-bold flex items-center justify-center gap-2 hover:scale-95 duration-200 text-sm">
                <span class="material-symbols-outlined">box_add</span> Input Barang Masuk
            </a>
        @endif
        <div class="mt-6 border-t border-outline-variant/20 pt-4 space-y-1">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-error w-full py-2 px-4 flex items-center gap-3 hover:translate-x-1 transition-transform text-sm">
                    <span class="material-symbols-outlined">logout</span>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Main Content -->
<main class="ml-64 min-h-screen">
    <!-- Top Header -->
    <header class="h-16 bg-surface-container-lowest flex items-center justify-between px-8 sticky top-0 z-40 border-b border-outline-variant/20 admin-card">
        <div class="flex items-center flex-1 max-w-xl">
            <div class="relative w-full">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input class="w-full bg-white border border-outline-variant/30 rounded-xl pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary"
                       placeholder="Cari produk, transaksi..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <p class="text-sm font-bold font-headline leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-on-surface-variant">Administrator</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold font-headline">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="p-8">
        @if(session('success'))
            <div class="mb-6 bg-secondary-fixed/30 text-on-secondary-fixed-variant px-4 py-3 rounded-xl flex items-center gap-3">
                <span class="material-symbols-outlined text-secondary">check_circle</span>
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
