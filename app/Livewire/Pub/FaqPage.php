<?php

namespace App\Livewire\Pub;

use App\Models\Faq;
use Livewire\Component;

class FaqPage extends Component
{
    public string $search = '';

    public function render()
    {
        $query = Faq::active();

        if ($this->search !== '') {
            $query->where(fn ($q) => $q->where('question', 'like', "%{$this->search}%")
                ->orWhere('answer', 'like', "%{$this->search}%"));
        }

        return view('livewire.pub.faq', [
            'groups' => $query->get()->groupBy('group'),
        ])->layout('components.layouts.public', ['title' => 'Pertanyaan Umum']);
    }
}
