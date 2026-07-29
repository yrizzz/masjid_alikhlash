<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Sumber data Al-Quran (equran.id). Hasil unduhan disimpan permanen di cache
 * sehingga setelah sekali diakses, halaman tetap dapat dibuka tanpa internet.
 */
class QuranService
{
    protected const BASE = 'https://equran.id/api/v2';

    /** Daftar 114 surah. @return array<int, array<string, mixed>> */
    public function surahs(): array
    {
        return Cache::rememberForever('quran.surahs', function () {
            $response = $this->get('/surat');

            return collect($response['data'] ?? [])
                ->map(fn ($s) => [
                    'nomor'       => $s['nomor'],
                    'nama'        => $s['nama'],
                    'namaLatin'   => $s['namaLatin'],
                    'jumlahAyat'  => $s['jumlahAyat'],
                    'tempatTurun' => $s['tempatTurun'],
                    'arti'        => $s['arti'],
                ])->all();
        });
    }

    /** Detail satu surah lengkap dengan ayat, terjemah, tafsir ringkas, dan audio. */
    public function surah(int $number): ?array
    {
        $number = max(1, min(114, $number));

        return Cache::rememberForever("quran.surah.{$number}", function () use ($number) {
            $response = $this->get("/surat/{$number}");

            return $response['data'] ?? null;
        });
    }

    /** Tafsir per ayat untuk satu surah. */
    public function tafsir(int $number): array
    {
        $number = max(1, min(114, $number));

        return Cache::rememberForever("quran.tafsir.{$number}", function () use ($number) {
            $response = $this->get("/tafsir/{$number}");

            return collect($response['data']['tafsir'] ?? [])->keyBy('ayat')->all();
        });
    }

    /** True bila data sudah tersedia (di cache atau lewat jaringan). */
    public function available(): bool
    {
        return ! empty($this->surahs());
    }

    protected function get(string $path): array
    {
        try {
            $response = Http::timeout(12)->acceptJson()->get(self::BASE.$path);

            return $response->successful() ? (array) $response->json() : [];
        } catch (\Throwable) {
            return [];
        }
    }
}
