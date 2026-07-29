<?php

namespace App\Livewire\Pub;

use App\Models\Article;
use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ArtikelIndex extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url]
    public string $kategori = '';

    public function updated(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Article::published()->with(['category', 'author']);

        if ($this->search !== '') {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('excerpt', 'like', "%{$this->search}%"));
        }
        if ($this->kategori !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->kategori));
        }

        return view('livewire.pub.artikel-index', [
            'articles'   => $query->latest('published_at')->paginate(9),
            'featured'   => Article::published()->where('is_featured', true)->latest('published_at')->first(),
            'categories' => Category::type('artikel')->get(),
            'popular'    => Article::published()->orderByDesc('views')->take(5)->get(),
        ])->layout('components.layouts.public', ['title' => 'Artikel']);
    }
}
