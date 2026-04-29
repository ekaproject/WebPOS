<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // ✅ Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 🔍 Ambil user
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $maxAttempt = $user->role === 'admin' ? 3 : 5;

            // ❗ Jika sudah limit DAN password salah → blok
            if ($user->login_attempt >= $maxAttempt && !Auth::attempt($credentials)) {
                return back()->withErrors([
                    'email' => 'Akun diblok karena terlalu banyak percobaan login.',
                ])->withInput();
            }
        }

        // 🔐 LOGIN UTAMA
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 🔁 Reset login attempt
            $user->login_attempt = 0;
            $user->save();

            // 📝 Log berhasil
            Log::info('Login berhasil: ' . $user->email . ' | Role: ' . $user->role);

            // 🔀 Redirect berdasarkan role
            if ($user->role === 'distributor') {
                return redirect()->intended(route('distributor.returns.index'));
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        // ❌ LOGIN GAGAL
        if ($user) {
            $user->login_attempt += 1;
            $user->save();

            Log::warning('Login gagal: ' . $request->email . ' | Percobaan ke-' . $user->login_attempt);
        } else {
            Log::warning('Login gagal: email tidak ditemukan - ' . $request->email);
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}