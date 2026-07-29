<?php

namespace App\Livewire\Pub;

use App\Models\Program;
use App\Models\Volunteer;
use Livewire\Component;

class VolunteerPage extends Component
{
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public array $interests = [];
    public string $skills = '';
    public string $availability = '';
    public string $motivation = '';

    public bool $done = false;

    public const INTERESTS = [
        'Kebersihan & Perawatan Masjid',
        'Dokumentasi & Media Sosial',
        'Pengajar TPQ',
        'Panitia Kajian & Acara',
        'Bakti Sosial & Santunan',
        'IT & Multimedia',
        'Keamanan & Parkir',
        'Konsumsi',
    ];

    public function mount(): void
    {
        if ($user = auth()->user()) {
            $this->name  = $user->name;
            $this->phone = (string) $user->phone;
            $this->email = (string) $user->email;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'name'      => 'required|min:3',
            'phone'     => 'required|min:8',
            'interests' => 'required|array|min:1',
        ], [
            'interests.required' => 'Pilih minimal satu bidang minat.',
        ], ['name' => 'nama', 'phone' => 'nomor WhatsApp']);

        Volunteer::create([
            'user_id'      => auth()->id(),
            'name'         => $this->name,
            'phone'        => $this->phone,
            'email'        => $this->email ?: null,
            'address'      => $this->address ?: null,
            'interests'    => $this->interests,
            'skills'       => $this->skills ?: null,
            'availability' => $this->availability ?: null,
            'motivation'   => $this->motivation ?: null,
            'status'       => 'pending',
        ]);

        $this->done = true;
        $this->dispatch('toast', message: 'Terima kasih! Pendaftaran relawan terkirim.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.pub.volunteer', [
            'interestOptions' => self::INTERESTS,
            'programs'        => Program::active()->take(6)->get(),
            'total'           => Volunteer::where('status', 'active')->count(),
        ])->layout('components.layouts.public', ['title' => 'Volunteer']);
    }
}
