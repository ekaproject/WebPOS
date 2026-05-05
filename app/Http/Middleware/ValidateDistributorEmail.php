<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateDistributorEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 🔍 Cek jika role yang ditambahkan adalah distributor
        if ($request->role === 'distributor') {
            $email = $request->email;

            // 🔒 Validasi email harus gmail.com atau polije.ac.id
            if (!preg_match('/@(gmail\.com|polije\.ac\.id)$/', $email)) {
                return back()->withErrors([
                    'email' => 'Email distributor harus menggunakan gmail.com atau polije.ac.id',
                ])->withInput();
            }
        }

        return $next($request);
    }
}