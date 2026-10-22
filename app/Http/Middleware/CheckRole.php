<?php

namespace App\Http\Middleware;

// Letak file: app/Http/Middleware/CheckRole.php
// Daftarkan di bootstrap/app.php (Laravel 11) dengan alias 'role', contoh ada di routes/web.php

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * @param  string  $roles  Daftar role yang diizinkan, dipisah koma. Contoh: 'admin' atau 'siswa,guru'
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $allowed = explode(',', $roles);

        if (! $request->user() || ! in_array($request->user()->role, $allowed, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}