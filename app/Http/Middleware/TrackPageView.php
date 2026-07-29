<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/** Mencatat kunjungan halaman publik untuk modul Analitik. */
class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $skip = $request->ajax()
            || $request->hasHeader('X-Livewire')
            || $request->is('admin', 'admin/*', 'livewire/*', 'img/*', 'up')
            || ! $request->isMethod('GET')
            || $response->getStatusCode() !== 200;

        if (! $skip) {
            PageView::create([
                'path'    => '/'.ltrim($request->path(), '/'),
                'referer' => substr((string) $request->header('referer'), 0, 255) ?: null,
                'ip'      => $request->ip(),
                'agent'   => substr((string) $request->userAgent(), 0, 255),
                'date'    => today(),
            ]);
        }

        return $response;
    }
}
