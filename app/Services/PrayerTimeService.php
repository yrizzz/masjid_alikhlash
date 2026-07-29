<?php

namespace App\Services;

use App\Models\Setting;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

/**
 * Kalkulasi waktu sholat secara lokal (tanpa API eksternal).
 *
 * Algoritma astronomi standar (posisi matahari — Meeus), sama dengan yang
 * dipakai PrayTimes.org. Parameter sudut default mengikuti Kemenag RI
 * (Subuh 20°, Isya 18°) plus ihtiyat 2 menit.
 */
class PrayerTimeService
{
    /** Urutan & label waktu sholat yang dipakai di seluruh aplikasi. */
    public const PRAYERS = [
        'imsak'   => 'Imsak',
        'subuh'   => 'Subuh',
        'terbit'  => 'Terbit',
        'dhuha'   => 'Dhuha',
        'dzuhur'  => 'Dzuhur',
        'ashar'   => 'Ashar',
        'maghrib' => 'Maghrib',
        'isya'    => 'Isya',
    ];

    /** Hanya lima waktu wajib — dipakai countdown & jadwal imam. */
    public const FARDH = ['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'];

    protected float $lat;
    protected float $lng;
    protected float $elevation;
    protected float $timezone;
    protected float $fajrAngle;
    protected float $ishaAngle;
    protected int $ihtiyat;
    protected float $jDate = 0;

    public function __construct(?float $lat = null, ?float $lng = null, ?float $timezone = null)
    {
        $this->lat       = $lat ?? (float) Setting::get('mosque_lat', -7.5544755);
        $this->lng       = $lng ?? (float) Setting::get('mosque_lng', 110.7955645);
        $this->timezone  = $timezone ?? (float) Setting::get('prayer_timezone', 7);
        $this->elevation = (float) Setting::get('mosque_elevation', 95);
        $this->fajrAngle = (float) Setting::get('prayer_fajr_angle', 20);
        $this->ishaAngle = (float) Setting::get('prayer_isha_angle', 18);
        $this->ihtiyat   = (int) Setting::get('prayer_ihtiyat', 2);
    }

    /**
     * Waktu sholat satu hari sebagai Carbon.
     *
     * @return array<string, CarbonImmutable>
     */
    public function forDate(?CarbonInterface $date = null): array
    {
        $date = CarbonImmutable::parse($date ?? now())->startOfDay();
        $raw  = $this->compute($date);

        $out = [];
        foreach ($raw as $key => $hours) {
            $out[$key] = $date->addMinutes((int) round($hours * 60));
        }

        return $out;
    }

    /** Hanya lima waktu wajib. @return array<string, CarbonImmutable> */
    public function fardhForDate(?CarbonInterface $date = null): array
    {
        return array_intersect_key($this->forDate($date), array_flip(self::FARDH));
    }

    /**
     * Status waktu sholat saat ini: yang sedang berlangsung, berikutnya,
     * sisa detik menuju adzan berikutnya, dan progres antar waktu.
     */
    public function status(?CarbonInterface $now = null): array
    {
        $now    = CarbonImmutable::parse($now ?? now());
        $today  = $this->fardhForDate($now);
        $labels = self::PRAYERS;

        $currentKey = null;
        $nextKey    = null;
        foreach ($today as $key => $time) {
            if ($now->greaterThanOrEqualTo($time)) {
                $currentKey = $key;
            } elseif ($nextKey === null) {
                $nextKey = $key;
            }
        }

        if ($nextKey !== null) {
            $nextTime = $today[$nextKey];
        } else {
            // Setelah Isya → Subuh besok.
            $nextKey  = 'subuh';
            $nextTime = $this->fardhForDate($now->addDay())['subuh'];
        }

        $currentTime = $currentKey ? $today[$currentKey] : $this->fardhForDate($now->subDay())['isya'];
        $currentKey ??= 'isya';

        $span     = max(1, $currentTime->diffInSeconds($nextTime));
        $elapsed  = max(0, $currentTime->diffInSeconds($now));
        $iqomah   = (int) Setting::get('iqomah_'.$currentKey, Setting::get('iqomah_default', 10));
        $iqomahAt = $currentTime->addMinutes($iqomah);

        return [
            'now'            => $now,
            'times'          => $today,
            'all'            => $this->forDate($now),
            'current'        => $currentKey,
            'current_label'  => $labels[$currentKey] ?? ucfirst($currentKey),
            'current_time'   => $currentTime,
            'next'           => $nextKey,
            'next_label'     => $labels[$nextKey] ?? ucfirst($nextKey),
            'next_time'      => $nextTime,
            'seconds_left'   => max(0, (int) $now->diffInSeconds($nextTime)),
            'progress'       => min(100, round($elapsed / $span * 100, 1)),
            'iqomah_at'      => $iqomahAt,
            'in_iqomah'      => $now->betweenIncluded($currentTime, $iqomahAt),
            'iqomah_minutes' => $iqomah,
        ];
    }

    /** Jadwal sebulan penuh. @return array<int, array<string, CarbonImmutable>> */
    public function forMonth(int $year, int $month): array
    {
        $start = CarbonImmutable::create($year, $month, 1);
        $out   = [];
        for ($d = 0; $d < $start->daysInMonth; $d++) {
            $date       = $start->addDays($d);
            $out[$d + 1] = ['date' => $date] + $this->forDate($date);
        }

        return $out;
    }

    /** Arah kiblat dalam derajat dari utara sejati. */
    public function qiblaDirection(): float
    {
        $kaabaLat = deg2rad(21.4225);
        $kaabaLng = deg2rad(39.8262);
        $lat      = deg2rad($this->lat);
        $lng      = deg2rad($this->lng);
        $dLng     = $kaabaLng - $lng;

        $y = sin($dLng);
        $x = cos($lat) * tan($kaabaLat) - sin($lat) * cos($dLng);

        return fmod(rad2deg(atan2($y, $x)) + 360, 360);
    }

    /** Jarak ke Ka'bah dalam km. */
    public function qiblaDistance(): float
    {
        $r    = 6371;
        $dLat = deg2rad(21.4225 - $this->lat);
        $dLng = deg2rad(39.8262 - $this->lng);
        $a    = sin($dLat / 2) ** 2 + cos(deg2rad($this->lat)) * cos(deg2rad(21.4225)) * sin($dLng / 2) ** 2;

        return round($r * 2 * atan2(sqrt($a), sqrt(1 - $a)));
    }

    public function coordinates(): array
    {
        return ['lat' => $this->lat, 'lng' => $this->lng];
    }

    // ── Inti perhitungan ────────────────────────────────────────────────

    /** @return array<string, float> jam desimal waktu lokal */
    protected function compute(CarbonImmutable $date): array
    {
        $this->jDate = $this->julian($date->year, $date->month, $date->day) - $this->lng / (15 * 24);

        // Tebakan awal (jam desimal) lalu disempurnakan lewat iterasi.
        $t = ['imsak' => 5 / 24, 'subuh' => 5 / 24, 'terbit' => 6 / 24, 'dzuhur' => 12 / 24,
              'ashar' => 13 / 24, 'maghrib' => 18 / 24, 'isya' => 18 / 24];

        for ($i = 0; $i < 3; $i++) {
            $t = [
                'imsak'   => $this->sunAngleTime($this->fajrAngle, $t['imsak'], true),
                'subuh'   => $this->sunAngleTime($this->fajrAngle, $t['subuh'], true),
                'terbit'  => $this->sunAngleTime($this->riseSetAngle(), $t['terbit'], true),
                'dzuhur'  => $this->midDay($t['dzuhur']),
                'ashar'   => $this->asrTime(1, $t['ashar']),
                'maghrib' => $this->sunAngleTime($this->riseSetAngle(), $t['maghrib']),
                'isya'    => $this->sunAngleTime($this->ishaAngle, $t['isya']),
            ];
        }

        $ih = $this->ihtiyat / 60;

        $times = [
            'imsak'   => $t['subuh'] - 10 / 60 + $ih,
            'subuh'   => $t['subuh'] + $ih,
            'terbit'  => $t['terbit'] - $ih,
            'dhuha'   => $t['terbit'] + 20 / 60,
            'dzuhur'  => $t['dzuhur'] + $ih + 1 / 60,
            'ashar'   => $t['ashar'] + $ih,
            'maghrib' => $t['maghrib'] + 1 / 60,
            'isya'    => $t['isya'] + $ih,
        ];

        // Koreksi zona waktu vs bujur lokal.
        $offset = $this->timezone - $this->lng / 15;

        return array_map(fn ($v) => $v + $offset, $times);
    }

    /** Sudut depresi matahari terbit/terbenam, dikoreksi ketinggian tempat. */
    protected function riseSetAngle(): float
    {
        return 0.833 + 0.0347 * sqrt(max(0, $this->elevation));
    }

    protected function julian(int $y, int $m, int $d): float
    {
        if ($m <= 2) {
            $y--;
            $m += 12;
        }
        $a = floor($y / 100);
        $b = 2 - $a + floor($a / 4);

        return floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $d + $b - 1524.5;
    }

    /** @return array{declination: float, equation: float} */
    protected function sunPosition(float $jd): array
    {
        $d = $jd - 2451545.0;
        $g = $this->fixAngle(357.529 + 0.98560028 * $d);
        $q = $this->fixAngle(280.459 + 0.98564736 * $d);
        $l = $this->fixAngle($q + 1.915 * $this->dsin($g) + 0.020 * $this->dsin(2 * $g));
        $e = 23.439 - 0.00000036 * $d;

        $ra   = $this->fixHour($this->darctan2($this->dcos($e) * $this->dsin($l), $this->dcos($l)) / 15);
        $eqt  = $q / 15 - $ra;
        $decl = $this->darcsin($this->dsin($e) * $this->dsin($l));

        return ['declination' => $decl, 'equation' => $eqt];
    }

    protected function midDay(float $t): float
    {
        $eqt = $this->sunPosition($this->jDate + $t)['equation'];

        return $this->fixHour(12 - $eqt);
    }

    protected function sunAngleTime(float $angle, float $t, bool $ccw = false): float
    {
        $decl = $this->sunPosition($this->jDate + $t)['declination'];
        $noon = $this->midDay($t);

        $num = -$this->dsin($angle) - $this->dsin($decl) * $this->dsin($this->lat);
        $den = $this->dcos($decl) * $this->dcos($this->lat);
        $ratio = $den == 0.0 ? 0 : max(-1, min(1, $num / $den));

        $offset = $this->darccos($ratio) / 15;

        return $noon + ($ccw ? -$offset : $offset);
    }

    protected function asrTime(float $factor, float $t): float
    {
        $decl  = $this->sunPosition($this->jDate + $t)['declination'];
        $angle = -$this->darccot($factor + tan(abs(deg2rad($this->lat - $decl))));

        return $this->sunAngleTime($angle, $t);
    }

    // Helper trigonometri berbasis derajat.
    protected function dsin(float $d): float { return sin(deg2rad($d)); }
    protected function dcos(float $d): float { return cos(deg2rad($d)); }
    protected function darcsin(float $x): float { return rad2deg(asin($x)); }
    protected function darccos(float $x): float { return rad2deg(acos($x)); }
    protected function darctan2(float $y, float $x): float { return rad2deg(atan2($y, $x)); }
    protected function darccot(float $x): float { return rad2deg(atan2(1, $x)); }

    protected function fixAngle(float $a): float
    {
        $a = fmod($a, 360);

        return $a < 0 ? $a + 360 : $a;
    }

    protected function fixHour(float $h): float
    {
        $h = fmod($h, 24);

        return $h < 0 ? $h + 24 : $h;
    }
}
