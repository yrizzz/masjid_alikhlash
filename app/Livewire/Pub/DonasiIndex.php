<?php

namespace App\Livewire\Pub;

use App\Models\Campaign;
use App\Models\Donation;
use Livewire\Component;

class DonasiIndex extends Component
{
    public string $filter = 'active';

    public function render()
    {
        $query = Campaign::query();
        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        return view('livewire.pub.donasi-index', [
            'campaigns'  => $query->orderByDesc('is_featured')->orderByDesc('created_at')->get(),
            'featured'   => Campaign::active()->where('is_featured', true)->first(),
            'recent'     => Donation::paid()->with('campaign')->latest('paid_at')->take(8)->get(),
            'totalRaised' => (float) Donation::paid()->sum('amount'),
            'donorCount' => Donation::paid()->count(),
        ])->layout('components.layouts.public', ['title' => 'Donasi']);
    }
}
