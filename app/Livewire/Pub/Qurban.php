<?php

namespace App\Livewire\Pub;

use App\Models\QurbanAnimal;
use App\Models\QurbanParticipant;
use Livewire\Component;

class Qurban extends Component
{
    public ?int $animalId = null;
    public string $name = '';
    public string $onBehalfOf = '';
    public string $phone = '';
    public int $slotCount = 1;
    public ?QurbanParticipant $created = null;

    public function mount(): void
    {
        if ($user = auth()->user()) {
            $this->name  = $user->name;
            $this->phone = (string) $user->phone;
        }
    }

    public function choose(int $id): void
    {
        $this->animalId = $id;
        $this->created = null;
    }

    public function register(): void
    {
        $this->validate([
            'animalId' => 'required|exists:qurban_animals,id',
            'name'     => 'required|min:3',
            'phone'    => 'required|min:8',
            'slotCount' => 'required|integer|min:1',
        ], [], ['animalId' => 'hewan qurban', 'name' => 'nama', 'phone' => 'nomor WhatsApp', 'slotCount' => 'jumlah slot']);

        $animal = QurbanAnimal::findOrFail($this->animalId);

        if ($animal->slots_left < $this->slotCount) {
            $this->addError('slotCount', 'Slot tersisa hanya '.$animal->slots_left.'.');

            return;
        }

        $this->created = QurbanParticipant::create([
            'qurban_animal_id' => $animal->id,
            'user_id'          => auth()->id(),
            'name'             => $this->name,
            'on_behalf_of'     => $this->onBehalfOf ?: $this->name,
            'phone'            => $this->phone,
            'slots'            => $this->slotCount,
            'amount'           => $animal->price_per_slot * $this->slotCount,
            'status'           => 'pending',
        ]);

        $animal->increment('slots_taken', $this->slotCount);
        if ($animal->fresh()->slots_left <= 0) {
            $animal->update(['status' => 'full']);
        }

        $this->dispatch('toast', message: 'Pendaftaran qurban tersimpan.', variant: 'success');
    }

    public function render()
    {
        $year = (int) now()->year;

        return view('livewire.pub.qurban', [
            'animals'      => QurbanAnimal::where('year', $year)->orderBy('type')->get(),
            'participants' => QurbanParticipant::whereHas('animal', fn ($q) => $q->where('year', $year))
                ->where('status', '!=', 'batal')->latest()->take(20)->get(),
            'year'         => $year,
        ])->layout('components.layouts.public', ['title' => 'Qurban']);
    }
}
