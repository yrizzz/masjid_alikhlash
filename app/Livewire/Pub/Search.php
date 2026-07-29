<?php

namespace App\Livewire\Pub;

use App\Models\Article;
use App\Models\Campaign;
use App\Models\Ebook;
use App\Models\Kajian;
use App\Models\Program;
use App\Models\UmkmBusiness;
use Livewire\Attributes\Url;
use Livewire\Component;

class Search extends Component
{
    #[Url(as: 'q')]
    public string $q = '';

    public function render()
    {
        $term = trim($this->q);
        $like = "%{$term}%";
        $empty = $term === '';

        return view('livewire.pub.search', [
            'kajians'  => $empty ? collect() : Kajian::published()->where(fn ($x) => $x->where('title', 'like', $like)->orWhere('ustadz', 'like', $like))->take(6)->get(),
            'articles' => $empty ? collect() : Article::published()->where(fn ($x) => $x->where('title', 'like', $like)->orWhere('excerpt', 'like', $like))->take(6)->get(),
            'campaigns' => $empty ? collect() : Campaign::where('title', 'like', $like)->take(4)->get(),
            'programs' => $empty ? collect() : Program::where('title', 'like', $like)->take(4)->get(),
            'ebooks'   => $empty ? collect() : Ebook::where('is_published', true)->where('title', 'like', $like)->take(4)->get(),
            'umkm'     => $empty ? collect() : UmkmBusiness::approved()->where('name', 'like', $like)->take(4)->get(),
        ])->layout('components.layouts.public', ['title' => 'Pencarian']);
    }
}
