<?php

namespace App\Livewire\Pub;

use App\Models\ContactMessage;
use App\Models\Pengurus;
use Livewire\Component;

class Kontak extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $subject = '';
    public string $message = '';
    public bool $sent = false;

    public function mount(): void
    {
        if ($user = auth()->user()) {
            $this->name  = $user->name;
            $this->email = (string) $user->email;
            $this->phone = (string) $user->phone;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'name'    => 'required|min:3',
            'message' => 'required|min:10',
            'email'   => 'nullable|email',
        ], [], ['name' => 'nama', 'message' => 'pesan', 'email' => 'email']);

        ContactMessage::create([
            'name'    => $this->name,
            'email'   => $this->email ?: null,
            'phone'   => $this->phone ?: null,
            'subject' => $this->subject ?: 'Tanpa subjek',
            'message' => $this->message,
        ]);

        $this->reset(['subject', 'message']);
        $this->sent = true;
        $this->dispatch('toast', message: 'Pesan terkirim. Terima kasih.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.pub.kontak', [
            'contacts' => Pengurus::active()->whereNotNull('phone')->where('level', 1)->get(),
        ])->layout('components.layouts.public', ['title' => 'Kontak']);
    }
}
