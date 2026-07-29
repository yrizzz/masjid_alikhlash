<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';

    public function sendReset()
    {
        $this->validate(['email' => ['required', 'email']]);

        // Pengiriman email belum dikonfigurasi — jamaah diarahkan menghubungi pengurus.
        session()->flash('status', 'Jika email tersebut terdaftar, tautan pemulihan akan dikirim. '
            .'Bila tidak menerima email, silakan hubungi sekretariat masjid.');
    }

    public function render()
    {
        return view('auth.forgot-password')->layout('components.layouts.guest', ['title' => 'Lupa Kata Sandi']);
    }
}
