<?php

use App\Models\Setting;
use Carbon\CarbonInterface;

if (! function_exists('rupiah')) {
    /** Format angka menjadi Rupiah: 1250000 → "Rp 1.250.000". */
    function rupiah(float|int|string|null $value, bool $withPrefix = true): string
    {
        $n = number_format((float) $value, 0, ',', '.');

        return $withPrefix ? 'Rp '.$n : $n;
    }
}

if (! function_exists('rupiah_short')) {
    /** Ringkas: 72500000 → "Rp 72,5 jt". */
    function rupiah_short(float|int|null $value): string
    {
        $v = (float) $value;

        return match (true) {
            $v >= 1_000_000_000 => 'Rp '.rtrim(rtrim(number_format($v / 1_000_000_000, 1, ',', '.'), '0'), ',').' M',
            $v >= 1_000_000     => 'Rp '.rtrim(rtrim(number_format($v / 1_000_000, 1, ',', '.'), '0'), ',').' jt',
            $v >= 1_000         => 'Rp '.rtrim(rtrim(number_format($v / 1_000, 1, ',', '.'), '0'), ',').' rb',
            default             => 'Rp '.number_format($v, 0, ',', '.'),
        };
    }
}

if (! function_exists('setting')) {
    /** Ambil nilai dari tabel settings, fallback ke config('masjid.*'). */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default ?? config("masjid.$key"));
    }
}

if (! function_exists('tanggal_id')) {
    /** Tanggal Indonesia: "Rabu, 29 Juli 2026". */
    function tanggal_id(?CarbonInterface $date = null, bool $withDay = true): string
    {
        $date  = $date ?? now();
        $days  = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $out = $date->day.' '.$bulan[$date->month].' '.$date->year;

        return $withDay ? $days[(int) $date->dayOfWeek].', '.$out : $out;
    }
}

if (! function_exists('img_url')) {
    /** URL gambar dari storage, dengan fallback placeholder bergradien. */
    function img_url(?string $path, ?string $seed = null): string
    {
        if ($path && str_starts_with($path, 'http')) {
            return $path;
        }
        if ($path) {
            return asset('storage/'.ltrim($path, '/'));
        }

        return route('placeholder', ['seed' => substr(md5($seed ?? 'masjid'), 0, 6)]);
    }
}

if (! function_exists('qr_svg')) {
    /** Render QR code sebagai SVG inline (tanpa berkas, tanpa layanan luar). */
    function qr_svg(string $text, int $size = 160): string
    {
        $writer = new BaconQrCode\Writer(
            new BaconQrCode\Renderer\ImageRenderer(
                new BaconQrCode\Renderer\RendererStyle\RendererStyle($size, 1),
                new BaconQrCode\Renderer\Image\SvgImageBackEnd()
            )
        );

        // Buang deklarasi XML agar aman disisipkan di tengah HTML.
        return preg_replace('/<\?xml.*?\?>\s*/', '', $writer->writeString($text));
    }
}
