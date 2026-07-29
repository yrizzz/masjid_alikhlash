<?php

namespace App\Livewire\Pub;

use App\Models\JumatSchedule;
use Livewire\Component;
use Livewire\WithPagination;

class Jumat extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.pub.jumat', [
            'next' => JumatSchedule::upcoming()->first(),
            'upcoming' => JumatSchedule::upcoming()->skip(1)->take(6)->get(),
            'past' => JumatSchedule::whereDate('date', '<', today())->orderByDesc('date')->paginate(10),
        ])->layout('components.layouts.public', ['title' => 'Jadwal Khatib Jumat']);
    }
}
