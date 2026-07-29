<?php

namespace App\Livewire\Pub;

use App\Models\Bookmark;
use App\Models\Donation;
use App\Models\KajianRegistration;
use App\Models\RoomBooking;
use Livewire\Component;

class Akun extends Component
{
    public string $tab = 'ringkasan';

    /* Profil */
    public string $name = '';
    public string $phone = '';
    public string $address = '';
    public string $occupation = '';

    public function mount(): void
    {
        $user = auth()->user();
        $this->name       = $user->name;
        $this->phone      = (string) $user->phone;
        $this->address    = (string) $user->address;
        $this->occupation = (string) $user->occupation;
    }

    public function saveProfile(): void
    {
        $this->validate(['name' => 'required|min:3'], [], ['name' => 'nama']);

        auth()->user()->update([
            'name'       => $this->name,
            'phone'      => $this->phone ?: null,
            'address'    => $this->address ?: null,
            'occupation' => $this->occupation ?: null,
        ]);

        $this->dispatch('toast', message: 'Profil diperbarui.', variant: 'success');
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.pub.akun', [
            'user'          => $user,
            'donations'     => $user->donations()->with('campaign')->latest()->get(),
            'totalDonation' => $user->total_donation,
            'registrations' => KajianRegistration::with('kajian')->where('user_id', $user->id)->latest()->get(),
            'bookings'      => RoomBooking::with('room')->where('user_id', $user->id)->latest()->get(),
            'bookmarks'     => Bookmark::with('bookmarkable')->where('user_id', $user->id)->latest()->get(),
            'quranMarks'    => $user->quranBookmarks()->whereIn('type', ['bookmark', 'note', 'last_read'])->latest()->get(),
            'volunteer'     => $user->volunteer,
            'lastDonations' => Donation::where('user_id', $user->id)->paid()->sum('amount'),
        ])->layout('components.layouts.public', ['title' => 'Akun Saya']);
    }
}
