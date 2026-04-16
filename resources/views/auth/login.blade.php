<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login | Manajemen ILS Mart</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @include('partials.vite-assets')
</head>
<body class="bg-mesh font-body text-on-surface min-h-screen flex items-center justify-center p-6">

<main class="w-full max-w-[1100px] grid grid-cols-1 lg:grid-cols-2 bg-surface-container-lowest rounded-[2rem] overflow-hidden shadow-[0px_12px_32px_rgba(26,28,30,0.06)] relative">

    <!-- Left Side: Brand Visual -->
    <section class="hidden lg:flex flex-col justify-between p-12 relative overflow-hidden"
             style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background: radial-gradient(circle at 20% 80%, #5cfd80 0%, transparent 50%);"></div>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-8">
                <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                <h1 class="font-headline font-extrabold text-2xl tracking-tighter text-white">ILS Mart</h1>
            </div>
            <h2 class="font-headline font-bold text-4xl text-white leading-tight max-w-md">
                Ekosistem untuk <span class="text-[#69ff87]">perdagangan terpadu</span> di semua sektor.
            </h2>
            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="flex items-center gap-2 text-white/80">
                    <span class="material-symbols-outlined text-sm">restaurant</span>
                    <span class="text-xs font-semibold uppercase tracking-wider">M&amp;B</span>
                </div>
                <div class="flex items-center gap-2 text-white/80">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                    <span class="text-xs font-semibold uppercase tracking-wider">FMCG</span>
                </div>
                <div class="flex items-center gap-2 text-white/80">
                    <span class="material-symbols-outlined text-sm">eco</span>
                    <span class="text-xs font-semibold uppercase tracking-wider">SEGAR</span>
                </div>
                <div class="flex items-center gap-2 text-white/80">
                    <span class="material-symbols-outlined text-sm">devices</span>
                    <span class="text-xs font-semibold uppercase tracking-wider">Digital</span>
                </div>
            </div>
        </div>
        <div class="relative z-10">
            <div class="flex gap-4 items-center">
                <div class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                    <span class="material-symbols-outlined text-white text-xl">admin_panel_settings</span>
                </div>
                <div>
                    <p class="text-white/80 font-medium text-sm">Pusat Kendali Terpadu</p>
                    <p class="text-white/50 text-xs">Gerbang aman untuk semua node bisnis</p>
                </div>
            </div>
            <div class="mt-8 border-t border-white/10 pt-8">
                <p class="text-white/40 text-[10px] uppercase tracking-[0.2em]">STATUS INTI PERUSAHAAN: OPERASIONAL</p>
            </div>
        </div>
    </section>

    <!-- Right Side: Login Form -->
    <section class="p-8 md:p-16 flex flex-col justify-center bg-surface-container-lowest">
        <div class="max-w-md mx-auto w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex items-center gap-2 mb-12">
                <span class="material-symbols-outlined text-primary text-3xl" style="font-variation-settings: 'FILL' 1;">grid_view</span>
                <span class="font-headline font-bold text-xl tracking-tight text-primary">ILS Mart</span>
            </div>

            <header class="mb-10">
                <h3 class="font-headline font-bold text-3xl text-on-surface mb-2">Login Sistem</h3>
                <p class="text-on-surface-variant font-medium">Akses rangkaian manajemen retail lengkap Anda</p>
            </header>

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2 ml-1" for="email">
                        ID Perusahaan / Email
                    </label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">badge</span>
                        <input class="w-full pl-12 pr-4 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary transition-all duration-200 text-on-surface placeholder:text-outline/60"
                               id="email" name="email" type="email" value="{{ old('email') }}"
                               placeholder="employee@megaretail.com" required autofocus/>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="block text-xs font-semibold text-on-surface-variant uppercase tracking-wider" for="password">
                            Kredensial
                        </label>
                    </div>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">key</span>
                        <input class="w-full pl-12 pr-12 py-4 bg-surface-container-highest border-none rounded-xl focus:ring-2 focus:ring-primary transition-all duration-200 text-on-surface placeholder:text-outline/60"
                               id="password" name="password" type="password"
                               placeholder="••••••••••••" required/>
                        <button class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface transition-colors"
                                type="button" onclick="togglePassword()">
                            <span class="material-symbols-outlined" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Remember -->
                <div class="flex items-center gap-2 px-1">
                    <input class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant"
                           id="remember" name="remember" type="checkbox"/>
                    <label class="text-sm text-on-surface-variant font-medium select-none" for="remember">
                        Perangkat terpercaya (30 hari)
                    </label>
                </div>

                <!-- Submit -->
                <button class="w-full py-4 text-on-primary font-bold rounded-full shadow-lg hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2 group"
                        style="background: linear-gradient(135deg, #003d9b 0%, #0052cc 100%);"
                        type="submit">
                    <span>Mulai Sesi</span>
                    <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">login</span>
                </button>
            </form>

            <footer class="mt-12 pt-8 border-t border-surface-variant">
                <p class="text-[10px] text-outline text-center">Properti Rahasia ILS Mart Corp. Akses tanpa izin dilarang. © 2024 ILS Mart.</p>
            </footer>
        </div>
    </section>

</main>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
</body>
</html>
