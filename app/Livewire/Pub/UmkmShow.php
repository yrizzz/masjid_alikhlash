<?php

namespace App\Livewire\Pub;

use App\Models\UmkmBusiness;
use Livewire\Component;

class UmkmShow extends Component
{
    public UmkmBusiness $business;

    public function mount(UmkmBusiness $business): void
    {
        abort_unless($business->status === 'approved', 404);
        $this->business = $business->load('products');
        $business->increment('views');
    }

    public function render()
    {
        return view('livewire.pub.umkm-show', [
            'others' => UmkmBusiness::approved()->where('id', '!=', $this->business->id)
                ->where('category_id', $this->business->category_id)->take(4)->get(),
        ])->layout('components.layouts.public', [
            'title'       => $this->business->name,
            'description' => $this->business->description,
        ]);
    }
}
