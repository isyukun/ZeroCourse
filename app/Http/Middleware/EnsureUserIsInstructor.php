<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsInstructor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan memiliki role instructor atau admin
        if (auth()->check() && (auth()->user()->role === 'instructor' || auth()->user()->role === 'admin')) {
            return $next($request);
        }

        // Jika bukan, lempar ke dashboard dengan pesan error
        return redirect()->route('dashboard')->with('error', 'Akses ditolak! Anda bukan instruktur.');
    }
}
