<?php

namespace App\Livewire\Pub;

use App\Models\Program;
use Livewire\Component;

class ProgramShow extends Component
{
    public Program $program;

    public function mount(Program $program): void
    {
        $this->program = $program;
    }

    public function render()
    {
        return view('livewire.pub.program-show', [
            'others' => Program::active()->where('id', '!=', $this->program->id)->take(4)->get(),
        ])->layout('components.layouts.public', [
            'title'       => $this->program->title,
            'description' => $this->program->excerpt,
        ]);
    }
}
