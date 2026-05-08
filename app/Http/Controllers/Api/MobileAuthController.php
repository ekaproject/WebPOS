<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MobileAuthController extends Controller
{
    /**
     * Login untuk aplikasi Mobile POS (role: kasir).
     * Mengembalikan token sederhana berbasis remember_token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'kasir')
            ->where('is_active', true)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah, atau akun tidak memiliki akses kasir.',
            ], 401);
        }

        // Buat token sederhana dan simpan ke remember_token
        $token = bin2hex(random_bytes(32));
        $user->forceFill(['remember_token' => $token])->save();

        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * Logout — hapus token.
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            $user = User::where('remember_token', $token)->first();
            if ($user) {
                $user->forceFill(['remember_token' => null])->save();
            }
        }

        return response()->json(['success' => true, 'message' => 'Logout berhasil.']);
    }

    /**
     * Cek token masih valid (untuk auto-login di Flutter).
     */
    public function me(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan.'], 401);
        }

        $user = User::where('remember_token', $token)
            ->where('role', 'kasir')
            ->where('is_active', true)
            ->first();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid atau sudah expired.'], 401);
        }

        return response()->json([
            'success' => true,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }
}
