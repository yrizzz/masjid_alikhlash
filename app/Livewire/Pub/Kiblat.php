<?php

namespace App\Livewire\Pub;

use App\Services\PrayerTimeService;
use Livewire\Component;

class Kiblat extends Component
{
    public function render()
    {
        $service = app(PrayerTimeService::class);

        return view('livewire.pub.kiblat', [
            'direction' => $service->qiblaDirection(),
            'distance'  => $service->qiblaDistance(),
            'coords'    => $service->coordinates(),
        ])->layout('components.layouts.public', ['title' => 'Arah Kiblat']);
    }
}
