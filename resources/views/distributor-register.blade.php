<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar Distributor | {{ $publicSettings['store_name'] }}</title>
    <meta name="description" content="Bergabung menjadi distributor {{ $publicSettings['store_name'] }}. Dapatkan keuntungan menarik dan jaringan distribusi yang luas."/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @include('partials.vite-assets')
    <style>
        .wa-icon { display: inline-block; width: 24px; height: 24px; flex-shrink: 0; }
    </style>
</head>
<body class="landing-surface font-body text-on-surface">

<!-- Navigation -->
@include('partials.navbar', [
    'active' => '',
    'authVariant' => 'dashboard',
    'hideAuthLink' => true,
])

<main class="pt-6 md:pt-8">

    <!-- Hero -->
    <section class="px-6 md:px-10 pt-4 md:pt-6 pb-10">
        <div class="page-hero-gradient p-8 md:p-12 lg:p-16">
            <div class="flex items-center gap-3 text-sm text-white/80 mb-6">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-base">chevron_right</span>
                <span class="text-white font-bold">Daftar Distributor</span>
            </div>

            <div class="max-w-3xl">
                <span class="inline-flex px-4 py-1.5 rounded-full bg-white/18 text-white font-headline text-xs font-bold mb-5 tracking-[0.2em] uppercase border border-white/30 backdrop-blur-sm">
                    Peluang Bisnis
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-headline font-extrabold tracking-tight text-white leading-[1.1] mb-5">
                    Bergabung Menjadi <br class="hidden md:inline"/>Distributor Kami
                </h1>
                <p class="text-base md:text-lg text-white/90 max-w-2xl leading-relaxed">
                    Jadilah bagian dari jaringan distribusi {{ $publicSettings['store_name'] }}. Dapatkan keuntungan menarik, dukungan penuh, dan akses ke produk berkualitas tinggi.
                </p>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="px-6 md:px-10 pb-14">
        <div class="rounded-[2rem] border border-white/65 bg-white/70 backdrop-blur-sm p-6 md:p-10 shadow-[0_16px_36px_rgba(2,54,97,0.08)]">
            <div class="text-center mb-10">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary/70">Mengapa Bergabung?</p>
                <h2 class="text-3xl md:text-4xl font-headline font-extrabold tracking-tight text-primary mt-2">Keuntungan Menjadi Distributor</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="promo-glass-card bg-white/88 border-white/80 p-6 text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                        <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">trending_up</span>
                    </div>
                    <h3 class="font-headline font-bold text-base mb-2">Harga Kompetitif</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Dapatkan harga khusus distributor yang jauh lebih kompetitif dibandingkan harga retail.</p>
                </div>

                <div class="promo-glass-card bg-white/88 border-white/80 p-6 text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                        <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">local_shipping</span>
                    </div>
                    <h3 class="font-headline font-bold text-base mb-2">Pengiriman Mudah</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Sistem pengiriman yang terorganisir dengan dukungan logistik terintegrasi.</p>
                </div>

                <div class="promo-glass-card bg-white/88 border-white/80 p-6 text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                        <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">support_agent</span>
                    </div>
                    <h3 class="font-headline font-bold text-base mb-2">Dukungan Penuh</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Tim support yang siap membantu Anda dalam setiap proses distribusi.</p>
                </div>

                <div class="promo-glass-card bg-white/88 border-white/80 p-6 text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                        <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">inventory</span>
                    </div>
                    <h3 class="font-headline font-bold text-base mb-2">Produk Berkualitas</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Akses ke ribuan produk berkualitas dari berbagai kategori kebutuhan.</p>
                </div>

                <div class="promo-glass-card bg-white/88 border-white/80 p-6 text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                        <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">dashboard</span>
                    </div>
                    <h3 class="font-headline font-bold text-base mb-2">Sistem Digital</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Kelola distribusi Anda secara digital melalui dashboard terintegrasi kami.</p>
                </div>

                <div class="promo-glass-card bg-white/88 border-white/80 p-6 text-center group hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-primary-fixed flex items-center justify-center mx-auto mb-4 group-hover:bg-[#0284C7] transition-colors">
                        <span class="material-symbols-outlined text-3xl text-primary group-hover:text-white transition-colors">handshake</span>
                    </div>
                    <h3 class="font-headline font-bold text-base mb-2">Kemitraan Jangka Panjang</h3>
                    <p class="text-sm text-on-surface-variant leading-relaxed">Bangun bisnis bersama kami dengan program kemitraan yang saling menguntungkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Requirements & CTA -->
    <section class="px-6 md:px-10 pb-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            <!-- Requirements -->
            <div class="rounded-[2rem] border border-white/65 bg-white/70 backdrop-blur-sm p-6 md:p-8 shadow-[0_16px_36px_rgba(2,54,97,0.08)] flex flex-col">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary/70 mb-2">Persyaratan</p>
                <h2 class="text-2xl md:text-3xl font-headline font-extrabold tracking-tight text-primary mb-6">Syarat Menjadi Distributor</h2>

                <div class="space-y-4 flex-1">
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/80 border border-white/70">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center flex-none">
                            <span class="material-symbols-outlined text-xl text-primary">badge</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-sm">Memiliki Badan Usaha</h4>
                            <p class="text-xs text-on-surface-variant mt-1">CV, PT, atau badan usaha resmi lainnya yang terdaftar.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/80 border border-white/70">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center flex-none">
                            <span class="material-symbols-outlined text-xl text-primary">warehouse</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-sm">Memiliki Gudang Penyimpanan</h4>
                            <p class="text-xs text-on-surface-variant mt-1">Fasilitas penyimpanan yang memadai untuk menjaga kualitas produk.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/80 border border-white/70">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center flex-none">
                            <span class="material-symbols-outlined text-xl text-primary">local_shipping</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-sm">Armada Pengiriman</h4>
                            <p class="text-xs text-on-surface-variant mt-1">Kendaraan operasional untuk distribusi produk ke area tujuan.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-white/80 border border-white/70">
                        <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center flex-none">
                            <span class="material-symbols-outlined text-xl text-primary">account_balance</span>
                        </div>
                        <div>
                            <h4 class="font-headline font-bold text-sm">Modal Awal</h4>
                            <p class="text-xs text-on-surface-variant mt-1">Kesiapan modal untuk pembelian stok awal produk distribusi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Card -->
            @php
                $waNumber = preg_replace('/[^0-9]/', '', $publicSettings['store_whatsapp'] ?? $publicSettings['store_phone'] ?? '6281234567890');
                $waMessage = urlencode("Halo, saya tertarik untuk mendaftar sebagai distributor " . ($publicSettings['store_name'] ?? 'ILS MART') . ". Mohon informasi lebih lanjut.");
            @endphp
            <div class="rounded-[2rem] overflow-hidden shadow-[0_16px_36px_rgba(2,54,97,0.15)] flex flex-col" style="background: linear-gradient(135deg, #7F1D1D 0%, #991B1B 40%, #0284C7 100%);">
                <div class="p-6 md:p-8 flex flex-col flex-1">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/70 mb-2">Daftar Sekarang</p>
                    <h2 class="text-2xl md:text-3xl font-headline font-extrabold tracking-tight text-white mb-4">Siap Bergabung?</h2>
                    <p class="text-white/85 text-sm leading-relaxed mb-6">
                        Hubungi kami melalui WhatsApp untuk proses pendaftaran distributor. Tim kami akan membantu Anda melalui setiap tahapan pendaftaran dengan cepat dan mudah.
                    </p>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center gap-3 text-white/90">
                            <span class="material-symbols-outlined text-lg flex-none" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span class="text-sm font-medium">Proses pendaftaran cepat</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90">
                            <span class="material-symbols-outlined text-lg flex-none" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span class="text-sm font-medium">Konsultasi gratis sebelum bergabung</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/90">
                            <span class="material-symbols-outlined text-lg flex-none" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <span class="text-sm font-medium">Respon cepat dalam 1x24 jam</span>
                        </div>
                    </div>

                    <div class="mt-auto">
                        <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           id="btn-wa-distributor"
                           style="display: inline-flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 16px 32px; border-radius: 16px; background-color: #25D366; color: #fff; font-weight: 700; font-size: 16px; text-decoration: none; transition: all 0.3s ease; font-family: 'Plus Jakarta Sans', sans-serif;"
                           onmouseover="this.style.backgroundColor='#1fb855'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(37,211,102,0.4)';"
                           onmouseout="this.style.backgroundColor='#25D366'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                            <svg style="width:24px;height:24px;flex-shrink:0;" viewBox="0 0 24 24" fill="#ffffff"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Hubungi via WhatsApp
                        </a>

                        <p class="text-center text-white/60 text-xs mt-4">
                            {{ $publicSettings['store_phone'] ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Distributor List -->
    @if($distributors->count())
    <section class="px-6 md:px-10 pb-14">
        <div class="rounded-[2rem] border border-white/65 bg-white/70 backdrop-blur-sm p-6 md:p-10 shadow-[0_16px_36px_rgba(2,54,97,0.08)]">
            <div class="text-center mb-10">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary/70">Mitra Kami</p>
                <h2 class="text-3xl md:text-4xl font-headline font-extrabold tracking-tight text-primary mt-2">Daftar Distributor Aktif</h2>
                <p class="text-on-surface-variant mt-2 max-w-xl mx-auto">Berikut adalah distributor resmi yang telah bergabung bersama {{ $publicSettings['store_name'] }}.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($distributors as $distributor)
                <div class="promo-glass-card bg-white/90 border-white/80 p-5 group hover:-translate-y-1 transition-transform duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-none" style="background: linear-gradient(135deg, var(--md-sys-color-primary) 0%, #0284C7 100%);">
                            <span class="text-white font-headline font-extrabold text-lg">{{ strtoupper(substr($distributor->name, 0, 1)) }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-headline font-bold text-sm leading-tight truncate">{{ $distributor->name }}</h3>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $distributor->code }}</p>
                        </div>
                    </div>

                    @if($distributor->contact_person)
                    <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
                        <span class="material-symbols-outlined text-sm text-primary/60 flex-none">person</span>
                        <span class="truncate">{{ $distributor->contact_person }}</span>
                    </div>
                    @endif

                    @if($distributor->phone)
                    <div class="flex items-center gap-2 text-xs text-on-surface-variant mb-2">
                        <span class="material-symbols-outlined text-sm text-primary/60 flex-none">call</span>
                        <span>{{ $distributor->phone }}</span>
                    </div>
                    @endif

                    @if($distributor->address)
                    <div class="flex items-start gap-2 text-xs text-on-surface-variant">
                        <span class="material-symbols-outlined text-sm text-primary/60 flex-none mt-0.5">location_on</span>
                        <span class="line-clamp-2">{{ $distributor->address }}</span>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
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
