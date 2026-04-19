<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Jika user belum login atau role-nya tidak sesuai, tendang dengan error 403
        if (!$request->user() || $request->user()->role !== $role) {
            abort(403, 'Akses Ditolak. Halaman ini khusus Supervisor.');
        }

        // Jika sesuai, persilakan masuk
        return $next($request);
    }
}