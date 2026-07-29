<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStaff
{
    /** Hanya pengurus (staff) yang boleh membuka dashboard admin. */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isStaff()) {
            abort(403, 'Halaman ini khusus pengurus masjid.');
        }

        return $next($request);
    }
}
