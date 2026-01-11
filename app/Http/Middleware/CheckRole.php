<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
{
    // Jika user punya role yang sesuai, silakan lewat
    if ($request->user() && in_array($request->user()->role, $roles)) {
        return $next($request);
    }

    // Jika tidak, tolak dengan error 403
    return response()->json(['message' => 'Akses Ditolak: Anda tidak punya izin.'], 403);
}
}
