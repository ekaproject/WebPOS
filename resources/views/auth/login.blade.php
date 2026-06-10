<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login | Manajemen ILS Mart</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @include('partials.vite-assets')
    <style>
        @keyframes login-fade-in {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-fade-in {
            animation: login-fade-in 0.45s ease-out forwards;
        }
    </style>
</head>
 <body class="min-h-screen text-slate-900 flex items-center justify-center p-5 md:p-6" style="font-family: 'Inter', 'Poppins', sans-serif; background: radial-gradient(circle at top, #fee2e2 0, #ffffff 42%, #fff 100%);">

<main class="w-full max-w-[400px]">
    <section class="rounded-xl border border-red-200 bg-white p-8 md:p-10 shadow-lg login-fade-in" style="box-shadow: 0 24px 60px rgba(220, 38, 38, 0.12);">
        <div class="mb-7 text-center">
            <p class="text-sm font-semibold tracking-wide text-[#dc2626]" style="font-family: 'Poppins', 'Inter', sans-serif;">ILS Mart</p>
        </div>

        <header class="mb-7">
            <h1 class="text-2xl md:text-[28px] font-bold text-slate-900" style="font-family: 'Poppins', 'Inter', sans-serif;">Masuk ke Akun</h1>
            <p class="text-slate-500 mt-1 text-sm">Silakan login untuk melanjutkan</p>
        </header>

        @if($errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}" id="loginForm" class="space-y-5">
            @csrf

           <div class="flex items-center w-full rounded-lg border border-slate-200 bg-white px-4 shadow-sm focus-within:ring-4 focus-within:ring-red-100 focus-within:border-[#dc2626]">

    <span class="material-symbols-outlined text-slate-400 mr-2 shrink-0">
        mail
    </span>

    <input 
        id="email" 
        name="email" 
        type="email"
        value="{{ old('email') }}"
        placeholder="contoh@domain.com"
        class="min-w-0 flex-1 py-3 text-slate-900 placeholder:text-slate-400 bg-transparent focus:outline-none"
        required
    />
</div>

           <div class="flex items-stretch w-full overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-red-100 focus-within:border-[#dc2626]">

    <span class="material-symbols-outlined flex items-center px-4 text-slate-400 shrink-0">
        key
    </span>

    <input 
        id="password" 
        name="password" 
        type="password"
        placeholder="Masukkan password"
        class="min-w-0 flex-1 py-3 pr-3 text-slate-900 placeholder:text-slate-400 bg-transparent focus:outline-none"
        required
    />

    <button type="button" onclick="togglePassword()" aria-label="Tampilkan atau sembunyikan password" class="flex w-12 items-center justify-center border-l border-slate-200 text-slate-400 transition-colors hover:bg-slate-50 hover:text-slate-700 cursor-pointer shrink-0">
        <span class="material-symbols-outlined" id="eyeIcon">visibility</span>
    </button>

</div>

            <div class="flex items-center gap-2 px-1">
                  <input class="h-4 w-4 rounded border-slate-300 text-[#dc2626] focus:ring-[#dc2626]"
                       id="remember" name="remember" type="checkbox"/>
                <label class="select-none text-sm text-slate-600" for="remember">
                    Ingat saya
                </label>
            </div>

            <button class="w-full rounded-lg py-3.5 font-semibold text-white shadow-md transition-all duration-200 hover:scale-[1.01] hover:shadow-lg active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-80"
                    id="submitButton" type="submit"
                    style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: #fff;">
                <span class="inline-flex items-center justify-center gap-2">
                    <svg aria-hidden="true" class="hidden h-5 w-5 animate-spin" id="submitSpinner" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span id="submitLabel">Masuk</span>
                </span>
            </button>
        </form>

        <p class="mt-7 border-t border-slate-100 pt-5 text-center text-[11px] text-slate-400">
            {{ date('Y') }} ILS Mart. Akses hanya untuk pengguna berwenang.
        </p>
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

document.getElementById('loginForm').addEventListener('submit', function () {
    const submitButton = document.getElementById('submitButton');
    const submitLabel = document.getElementById('submitLabel');
    const submitSpinner = document.getElementById('submitSpinner');

    submitButton.disabled = true;
    submitLabel.textContent = 'Memproses...';
    submitSpinner.classList.remove('hidden');
});
</script>
</body>
</html>
