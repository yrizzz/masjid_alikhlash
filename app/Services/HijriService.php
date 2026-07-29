<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use IntlDateFormatter;

/**
 * Konversi Masehi ⇄ Hijriah memakai kalender Umm al-Qura (ekstensi intl),
 * dengan fallback algoritma tabular bila intl tidak tersedia.
 */
class HijriService
{
    public const MONTHS = [
        1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal', 4 => 'Rabiul Akhir',
        5 => 'Jumadil Awal', 6 => 'Jumadil Akhir', 7 => 'Rajab', 8 => 'Syaban',
        9 => 'Ramadhan', 10 => 'Syawal', 11 => 'Dzulqadah', 12 => 'Dzulhijjah',
    ];

    public const DAYS = ['Ahad', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    /** Hari besar Islam: [bulan hijriah, tanggal] => nama. */
    public const HOLIDAYS = [
        '1-1'    => 'Tahun Baru Hijriah',
        '1-10'   => 'Hari Asyura',
        '3-12'   => 'Maulid Nabi Muhammad ﷺ',
        '7-27'   => 'Isra Mi\'raj',
        '8-15'   => 'Nisfu Syaban',
        '9-1'    => 'Awal Ramadhan',
        '9-17'   => 'Nuzulul Quran',
        '10-1'   => 'Idul Fitri',
        '10-2'   => 'Idul Fitri (hari kedua)',
        '12-9'   => 'Hari Arafah',
        '12-10'  => 'Idul Adha',
        '12-11'  => 'Hari Tasyrik',
        '12-12'  => 'Hari Tasyrik',
        '12-13'  => 'Hari Tasyrik',
    ];

    /** @return array{day:int, month:int, year:int, month_name:string, formatted:string} */
    public function convert(?CarbonInterface $date = null): array
    {
        $date = CarbonImmutable::parse($date ?? now());

        // Maghrib menandai pergantian hari hijriah — tapi untuk tampilan
        // umum kita pakai batas tengah malam agar konsisten dengan kalender.
        [$y, $m, $d] = $this->toHijri($date);

        return [
            'day'        => $d,
            'month'      => $m,
            'year'       => $y,
            'month_name' => self::MONTHS[$m] ?? '',
            'day_name'   => self::DAYS[(int) $date->dayOfWeek],
            'formatted'  => sprintf('%d %s %d H', $d, self::MONTHS[$m] ?? '', $y),
        ];
    }

    public function format(?CarbonInterface $date = null): string
    {
        return $this->convert($date)['formatted'];
    }

    /** Nama hari besar Islam pada tanggal tersebut, bila ada. */
    public function holiday(?CarbonInterface $date = null): ?string
    {
        $h = $this->convert($date);

        return self::HOLIDAYS[$h['month'].'-'.$h['day']] ?? null;
    }

    /**
     * Semua hari besar Islam dalam rentang tanggal Masehi.
     *
     * @return array<int, array{date: CarbonImmutable, name: string, hijri: string}>
     */
    public function holidaysBetween(CarbonInterface $from, CarbonInterface $to): array
    {
        $out     = [];
        $cursor  = CarbonImmutable::parse($from)->startOfDay();
        $endDate = CarbonImmutable::parse($to)->startOfDay();

        while ($cursor->lessThanOrEqualTo($endDate)) {
            if ($name = $this->holiday($cursor)) {
                $out[] = ['date' => $cursor, 'name' => $name, 'hijri' => $this->format($cursor)];
            }
            $cursor = $cursor->addDay();
        }

        return $out;
    }

    /** Tanggal Masehi dari tanggal Hijriah (pencarian biner pada rentang wajar). */
    public function toGregorian(int $hy, int $hm, int $hd): CarbonImmutable
    {
        // Perkiraan awal: 1 tahun hijriah ≈ 354.367 hari sejak 16 Juli 622 M.
        $guess = CarbonImmutable::create(622, 7, 16)
            ->addDays((int) round((($hy - 1) * 354.367) + (($hm - 1) * 29.5) + ($hd - 1)));

        // Koreksi maju/mundur maksimal 5 hari.
        for ($i = -5; $i <= 5; $i++) {
            $candidate = $guess->addDays($i);
            [$y, $m, $d] = $this->toHijri($candidate);
            if ($y === $hy && $m === $hm && $d === $hd) {
                return $candidate;
            }
        }

        return $guess;
    }

    /** @return array{0:int,1:int,2:int} [tahun, bulan, hari] */
    protected function toHijri(CarbonImmutable $date): array
    {
        if (class_exists(IntlDateFormatter::class)) {
            $fmt = new IntlDateFormatter(
                'en_US@calendar=islamic-umalqura',
                IntlDateFormatter::FULL,
                IntlDateFormatter::NONE,
                $date->getTimezone()->getName(),
                IntlDateFormatter::TRADITIONAL,
                'yyyy-MM-dd'
            );
            $parts = explode('-', (string) $fmt->format($date->getTimestamp()));
            if (count($parts) === 3) {
                return [(int) $parts[0], (int) $parts[1], (int) $parts[2]];
            }
        }

        return $this->tabular($date);
    }

    /** Fallback: algoritma tabular Islamic (Kuwaiti). */
    protected function tabular(CarbonImmutable $date): array
    {
        $jd = gregoriantojd($date->month, $date->day, $date->year);
        $l  = $jd - 1948440 + 10632;
        $n  = intdiv($l - 1, 10631);
        $l  = $l - 10631 * $n + 354;
        $j  = intdiv(10985 - $l, 5316) * intdiv(50 * $l, 17719) + intdiv($l, 5670) * intdiv(43 * $l, 15238);
        $l  = $l - intdiv(30 - $j, 15) * intdiv(17719 * $j, 50) - intdiv($j, 16) * intdiv(15238 * $j, 43) + 29;
        $m  = intdiv(24 * $l, 709);
        $d  = $l - intdiv($m * 709, 24);
        $y  = 30 * $n + $j - 30;

        return [$y, $m, $d];
    }
}
