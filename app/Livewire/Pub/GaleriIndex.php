<?php

namespace App\Livewire\Pub;

use App\Models\Category;
use App\Models\Gallery;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class GaleriIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $kategori = '';

    public function render()
    {
        $query = Gallery::where('is_published', true)->with(['category', 'photos'])->withCount('photos');

        if ($this->kategori !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->kategori));
        }

        return view('livewire.pub.galeri-index', [
            'galleries'  => $query->latest('taken_at')->paginate(12),
            'categories' => Category::type('galeri')->get(),
        ])->layout('components.layouts.public', ['title' => 'Galeri']);
    }
}
