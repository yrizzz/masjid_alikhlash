<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use App\Services\PrayerTimeService;
use Livewire\Component;

class Prayer extends Component
{
    public int $year;
    public int $month;

    /** Parameter perhitungan. */
    public array $params = [];

    /** Menit iqomah per waktu sholat. */
    public array $iqomah = [];

    public function mount(): void
    {
        $this->year  = (int) now()->year;
        $this->month = (int) now()->month;

        $this->params = [
            'mosque_lat'       => (string) Setting::get('mosque_lat', config('masjid.lat')),
            'mosque_lng'       => (string) Setting::get('mosque_lng', config('masjid.lng')),
            'mosque_elevation' => (string) Setting::get('mosque_elevation', 95),
            'prayer_fajr_angle' => (string) Setting::get('prayer_fajr_angle', 20),
            'prayer_isha_angle' => (string) Setting::get('prayer_isha_angle', 18),
            'prayer_ihtiyat'   => (string) Setting::get('prayer_ihtiyat', 2),
            'prayer_timezone'  => (string) Setting::get('prayer_timezone', 7),
        ];

        foreach (PrayerTimeService::FARDH as $p) {
            $this->iqomah[$p] = (string) Setting::get('iqomah_'.$p, 10);
        }
    }

    public function save(): void
    {
        foreach ($this->params as $key => $value) {
            Setting::put($key, $value, 'sholat');
        }
        foreach ($this->iqomah as $prayer => $minutes) {
            Setting::put('iqomah_'.$prayer, $minutes, 'sholat');
        }

        $this->dispatch('toast', message: 'Parameter waktu sholat disimpan.', variant: 'success');
    }

    public function render()
    {
        // Instansiasi baru agar memakai setelan terkini.
        $service = new PrayerTimeService();

        return view('livewire.admin.prayer', [
            'schedule' => $service->forMonth($this->year, $this->month),
            'qibla'    => $service->qiblaDirection(),
            'prayers'  => PrayerTimeService::PRAYERS,
        ])->layout('components.layouts.app', [
            'title'       => 'Waktu Sholat',
            'breadcrumbs' => [['label' => 'Ibadah'], ['label' => 'Waktu Sholat']],
        ]);
    }
}
