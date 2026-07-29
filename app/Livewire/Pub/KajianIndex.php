<?php

namespace App\Livewire\Pub;

use App\Models\Category;
use App\Models\Kajian;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class KajianIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $kategori = '';

    #[Url]
    public string $media = '';

    #[Url]
    public string $ustadz = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'kategori', 'media', 'ustadz']);
    }

    public function render()
    {
        $query = Kajian::published()->with('category');

        if ($this->search !== '') {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('excerpt', 'like', "%{$this->search}%")
                ->orWhere('ustadz', 'like', "%{$this->search}%"));
        }
        if ($this->kategori !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->kategori));
        }
        if ($this->media !== '') {
            $query->where('media_type', $this->media);
        }
        if ($this->ustadz !== '') {
            $query->where('ustadz', $this->ustadz);
        }

        return view('livewire.pub.kajian-index', [
            'kajians'    => $query->orderByRaw('start_at IS NULL, start_at DESC')->paginate(9),
            'categories' => Category::type('kajian')->get(),
            'ustadzList' => Kajian::published()->distinct()->orderBy('ustadz')->pluck('ustadz'),
            'upcoming'   => Kajian::published()->upcoming()->take(3)->get(),
        ])->layout('components.layouts.public', ['title' => 'Kajian']);
    }
}
