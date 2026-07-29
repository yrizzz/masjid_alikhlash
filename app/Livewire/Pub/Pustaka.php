<?php

namespace App\Livewire\Pub;

use App\Models\Category;
use App\Models\Ebook;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Pustaka extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $jenis = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Ebook::where('is_published', true)->with('category');

        if ($this->search !== '') {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('author', 'like', "%{$this->search}%"));
        }
        if ($this->jenis !== '') {
            $query->where('type', $this->jenis);
        }

        return view('livewire.pub.pustaka', [
            'ebooks'     => $query->latest()->paginate(12),
            'categories' => Category::type('ebook')->get(),
            'types'      => ['pdf' => 'PDF', 'kitab' => 'Kitab', 'slide' => 'Slide', 'video' => 'Video', 'audio' => 'Audio'],
        ])->layout('components.layouts.public', ['title' => 'E-Library']);
    }
}
