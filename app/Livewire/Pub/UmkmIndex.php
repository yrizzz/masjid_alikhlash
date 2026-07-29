<?php

namespace App\Livewire\Pub;

use App\Models\Category;
use App\Models\UmkmBusiness;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UmkmIndex extends Component
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
        $query = UmkmBusiness::approved()->with(['category', 'products']);

        if ($this->search !== '') {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%")
                ->orWhere('owner', 'like', "%{$this->search}%"));
        }
        if ($this->kategori !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $this->kategori));
        }

        return view('livewire.pub.umkm-index', [
            'businesses' => $query->orderByDesc('is_featured')->latest()->paginate(12),
            'categories' => Category::type('umkm')->get(),
            'total'      => UmkmBusiness::approved()->count(),
        ])->layout('components.layouts.public', ['title' => 'Marketplace UMKM Jamaah']);
    }
}
