<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

/**
 * Placeholder gambar bergradien — dipakai saat konten belum punya foto,
 * sehingga tata letak tetap rapi tanpa bergantung layanan luar.
 */
class PlaceholderController extends Controller
{
    public function __invoke(string $seed): Response
    {
        $hue  = hexdec(substr(md5($seed), 0, 2)) / 255 * 360;
        $hue2 = fmod($hue + 40, 360);

        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600" width="800" height="600">
          <defs>
            <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0%" stop-color="hsl({$hue}, 45%, 42%)"/>
              <stop offset="100%" stop-color="hsl({$hue2}, 55%, 22%)"/>
            </linearGradient>
            <pattern id="p" width="64" height="64" patternUnits="userSpaceOnUse">
              <path d="M32 0 L64 32 L32 64 L0 32 Z" fill="none" stroke="rgba(255,255,255,.09)" stroke-width="1.5"/>
            </pattern>
          </defs>
          <rect width="800" height="600" fill="url(#g)"/>
          <rect width="800" height="600" fill="url(#p)"/>
          <g fill="rgba(255,255,255,.14)" transform="translate(400 300)">
            <path d="M-110 90 h220 v-70 a110 110 0 0 0 -220 0 z"/>
            <rect x="-6" y="-190" width="12" height="60" rx="6"/>
            <circle cx="0" cy="-196" r="14"/>
          </g>
        </svg>
        SVG;

        return response($svg, 200, [
            'Content-Type'  => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
