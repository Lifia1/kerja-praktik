<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return redirect('/login');
        }

        $userRole = $request->user()->role;

        // Admin boleh akses semua halaman
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Operator hanya boleh akses role yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Forbidden
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}