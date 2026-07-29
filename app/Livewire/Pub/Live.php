<?php

namespace App\Livewire\Pub;

use App\Models\Livestream;
use Livewire\Component;

class Live extends Component
{
    public function render()
    {
        return view('livewire.pub.live', [
            'current'   => Livestream::where('status', 'live')->latest()->first()
                ?? Livestream::where('status', 'scheduled')->orderBy('start_at')->first(),
            'scheduled' => Livestream::where('status', 'scheduled')->orderBy('start_at')->get(),
            'archive'   => Livestream::where('status', 'ended')->latest('start_at')->take(9)->get(),
            'platforms' => Livestream::PLATFORMS,
        ])->layout('components.layouts.public', ['title' => 'Live Streaming']);
    }
}
