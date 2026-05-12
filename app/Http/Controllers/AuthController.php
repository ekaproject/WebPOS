<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // 3. Jika user tidak ditemukan
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email tidak ditemukan',
            ]);
        }

        // 4. Tentukan batas percobaan login
        $maxAttempt = $user->role === 'admin' ? 3 : 5;

        // 5. Blokir jika sudah melebihi limit
        if ($user->login_attempt >= $maxAttempt) {
            throw ValidationException::withMessages([
                'email' => 'Akun diblok karena terlalu banyak percobaan login.',
            ]);
        }

        // 6. Proses login (HANYA SEKALI - ini penting)
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // ambil user login
            $authUser = Auth::user();

            // reset login attempt
            $authUser->login_attempt = 0;
            $authUser->save();

            // log sukses login
            Log::info('Login berhasil: ' . $authUser->email . ' | Role: ' . $authUser->role);

            // redirect berdasarkan role
            if ($authUser->role === 'distributor') {
                return redirect()->intended(route('distributor.returns.index'));
            }

            if ($authUser->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            // fallback untuk role lain: kirim ke halaman utama
            return redirect()->intended(route('home'));
        }

        // DEBUG: Login failed
        session()->put('debug_login_status', 'ATTEMPT_FAILED');
        session()->put('debug_email_attempt', $request->email);
        session()->put('debug_user_exists', $user ? true : false);
        session()->save();

        // 7. Jika login gagal → tambah attempt
        $user->login_attempt += 1;
        $user->save();

        // log gagal login
        Log::warning('Login gagal: ' . $request->email . ' | Attempt ke-' . $user->login_attempt);

        // 8. Response error login
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}