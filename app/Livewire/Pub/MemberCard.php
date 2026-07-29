<?php

namespace App\Livewire\Pub;

use Illuminate\Support\Str;
use Livewire\Component;

class MemberCard extends Component
{
    public function mount(): void
    {
        $user = auth()->user();

        // Terbitkan nomor anggota pada saat pertama kartu dibuka.
        if (! $user->member_no) {
            $user->update(['member_no' => 'AIK-'.now()->format('y').'-'.Str::upper(Str::random(6))]);
        }
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.pub.member-card', [
            'user'   => $user,
            'roles'  => array_filter([
                $user->role_label,
                $user->total_donation > 0 ? 'Donatur' : null,
                $user->volunteer?->status === 'active' ? 'Volunteer' : null,
            ]),
            'total'  => $user->total_donation,
        ])->layout('components.layouts.public', ['title' => 'Kartu Anggota']);
    }
}
