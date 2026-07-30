<?php

namespace App\Livewire\Pub;

use App\Models\Campaign;
use App\Models\Donation;
use Livewire\Component;
use Livewire\WithPagination;

class DonasiIndex extends Component
{
    use WithPagination;

    public string $filter = 'active';

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Campaign::query();
        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return view('livewire.pub.donasi-index', [
            'campaigns'   => $query->orderByDesc('is_featured')->orderByDesc('created_at')->paginate(9),
            'featured'    => Campaign::active()->where('is_featured', true)->first(),
            'recent'      => Donation::paid()->with('campaign')->latest('paid_at')->take(8)->get(),
            'totalRaised' => (float) Donation::paid()->sum('amount'),
            'donorCount'  => Donation::paid()->count(),
        ])->layout('components.layouts.public', ['title' => 'Donasi']);
    }
}
