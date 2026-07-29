<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $this->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau kata sandi tidak cocok dengan data kami.');

            return;
        }

        Session::regenerate();

        $home = auth()->user()->isStaff() ? route('admin.dashboard') : route('akun');

        $this->redirectIntended($home, navigate: true);
    }

    public function render()
    {
        return view('auth.login')->layout('components.layouts.guest', ['title' => 'Masuk']);
    }
}
