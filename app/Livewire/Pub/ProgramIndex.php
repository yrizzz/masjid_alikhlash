<?php

namespace App\Livewire\Pub;

use App\Models\Program;
use Livewire\Component;

class ProgramIndex extends Component
{
    public function render()
    {
        return view('livewire.pub.program-index', [
            'programs' => Program::whereIn('status', ['active', 'selesai'])->orderBy('order')->get()->groupBy('type'),
            'types'    => [
                'ramadhan' => 'Ramadhan', 'qurban' => 'Qurban', 'zakat' => 'Zakat',
                'tpq' => 'TPQ', 'remaja' => 'Remaja Masjid', 'baksos' => 'Bakti Sosial', 'umum' => 'Program Lain',
            ],
        ])->layout('components.layouts.public', ['title' => 'Program Masjid']);
    }
}
