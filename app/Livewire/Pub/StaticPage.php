<?php

namespace App\Livewire\Pub;

use App\Models\Page;
use Livewire\Component;

class StaticPage extends Component
{
    public Page $page;

    public function mount(Page $page): void
    {
        abort_unless($page->is_published, 404);
        $this->page = $page;
    }

    public function render()
    {
        return view('livewire.pub.static-page')
            ->layout('components.layouts.public', [
                'title'       => $this->page->title,
                'description' => $this->page->meta_description,
            ]);
    }
}
