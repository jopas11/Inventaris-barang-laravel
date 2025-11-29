<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah pengguna terautentikasi
        if (!Auth::check()) {
            abort(403, 'Unauthorized action. Please log in.');
        }

        // Ambil pengguna yang terautentikasi
        $user = Auth::user();

        // Cek apakah role pengguna sesuai dengan role yang diizinkan
        if ($user->role !== $role) {
            abort(403, 'Unauthorized action. You do not have the required role.');
        }

        // Lanjutkan request jika semua pengecekan berhasil
        return $next($request);
    }
}