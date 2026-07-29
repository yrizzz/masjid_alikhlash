<?php

namespace App\Livewire\Pub;

use App\Models\ImamSchedule;
use App\Services\PrayerTimeService;
use Livewire\Component;

class Imam extends Component
{
    public function render()
    {
        return view('livewire.pub.imam', [
            'schedules' => ImamSchedule::get()->groupBy('day_of_week'),
            'days'      => ImamSchedule::DAYS,
            'prayers'   => ImamSchedule::PRAYERS,
            'times'     => app(PrayerTimeService::class)->fardhForDate(),
            'today'     => (int) now()->dayOfWeek,
        ])->layout('components.layouts.public', ['title' => 'Jadwal Imam & Muadzin']);
    }
}
