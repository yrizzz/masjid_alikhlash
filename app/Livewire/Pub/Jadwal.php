<?php

namespace App\Livewire\Pub;

use App\Services\HijriService;
use App\Services\PrayerTimeService;
use Livewire\Component;

class Jadwal extends Component
{
    public int $year;
    public int $month;

    public function mount(): void
    {
        $this->year  = (int) now()->year;
        $this->month = (int) now()->month;
    }

    public function shift(int $delta): void
    {
        $date = \Carbon\Carbon::create($this->year, $this->month, 1)->addMonths($delta);
        $this->year  = $date->year;
        $this->month = $date->month;
    }

    public function render()
    {
        $service = app(PrayerTimeService::class);

        return view('livewire.pub.jadwal', [
            'prayer'   => $service->status(),
            'schedule' => $service->forMonth($this->year, $this->month),
            'prayers'  => PrayerTimeService::PRAYERS,
            'hijri'    => app(HijriService::class)->convert(),
            'label'    => \Carbon\Carbon::create($this->year, $this->month)->translatedFormat('F Y'),
        ])->layout('components.layouts.public', ['title' => 'Jadwal Sholat']);
    }
}
