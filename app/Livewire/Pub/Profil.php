<?php

namespace App\Livewire\Pub;

use App\Models\Facility;
use App\Models\Milestone;
use App\Models\Pengurus;
use App\Models\Setting;
use Livewire\Component;

class Profil extends Component
{
    public function render()
    {
        return view('livewire.pub.profil', [
            'history'    => Setting::get('history'),
            'vision'     => Setting::get('vision'),
            'mission'    => array_filter(array_map('trim', explode("\n", (string) Setting::get('mission')))),
            'legality'   => Setting::get('legality'),
            'founded'    => Setting::get('founded'),
            'landArea'   => Setting::get('land_area'),
            'capacity'   => Setting::get('capacity'),
            'tour'       => Setting::get('virtual_tour'),
            'leaders'    => Pengurus::active()->where('level', 1)->get(),
            'staff'      => Pengurus::active()->where('level', '>', 1)->get()->groupBy('division'),
            'facilities' => Facility::orderBy('order')->get(),
            'milestones' => Milestone::orderBy('year')->get(),
        ])->layout('components.layouts.public', ['title' => 'Profil Masjid']);
    }
}
