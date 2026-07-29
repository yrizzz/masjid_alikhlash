<?php

namespace App\Livewire\Pub;

use App\Models\Gallery;
use Livewire\Component;

class GaleriShow extends Component
{
    public Gallery $gallery;

    public function mount(Gallery $gallery): void
    {
        abort_unless($gallery->is_published, 404);
        $this->gallery = $gallery->load('photos');
    }

    public function render()
    {
        return view('livewire.pub.galeri-show', [
            'others' => Gallery::where('is_published', true)->where('id', '!=', $this->gallery->id)
                ->latest('taken_at')->take(4)->get(),
        ])->layout('components.layouts.public', ['title' => $this->gallery->title]);
    }
}
