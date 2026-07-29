<?php

namespace App\Livewire\Admin;

use App\Models\Attendance;
use App\Models\KajianRegistration;
use App\Models\User;
use Livewire\Component;

class Checkin extends Component
{
    public string $code = '';
    public ?array $result = null;

    /** Terima kode pendaftaran kajian (KJ-…) atau nomor anggota jamaah. */
    public function scan(): void
    {
        $code = trim($this->code);
        $this->code = '';

        if ($code === '') {
            return;
        }

        if ($registration = KajianRegistration::with('kajian')->where('code', $code)->first()) {
            if ($registration->checked_in_at) {
                $this->result = ['ok' => false, 'title' => 'Sudah check-in', 'body' => $registration->name.' sudah tercatat pukul '.$registration->checked_in_at->format('H:i')];

                return;
            }

            $registration->update(['checked_in_at' => now()]);
            Attendance::create([
                'user_id'         => $registration->user_id,
                'name'            => $registration->name,
                'context'         => 'kajian',
                'attendable_type' => 'kajian',
                'attendable_id'   => $registration->kajian_id,
                'checked_in_at'   => now(),
            ]);

            $this->result = ['ok' => true, 'title' => 'Check-in berhasil', 'body' => $registration->name.' — '.$registration->kajian?->title];

            return;
        }

        if ($user = User::where('member_no', $code)->first()) {
            Attendance::create([
                'user_id'       => $user->id,
                'name'          => $user->name,
                'context'       => 'jumat',
                'checked_in_at' => now(),
            ]);

            $this->result = ['ok' => true, 'title' => 'Kartu anggota terverifikasi', 'body' => $user->name.' — '.$user->role_label];

            return;
        }

        $this->result = ['ok' => false, 'title' => 'Kode tidak dikenali', 'body' => 'Periksa kembali kode QR: '.$code];
    }

    public function render()
    {
        return view('livewire.admin.checkin', [
            'today'  => Attendance::whereDate('checked_in_at', today())->latest('checked_in_at')->take(25)->get(),
            'total'  => Attendance::whereDate('checked_in_at', today())->count(),
        ])->layout('components.layouts.app', [
            'title'       => 'QR Check-in',
            'breadcrumbs' => [['label' => 'Layanan'], ['label' => 'QR Check-in']],
        ]);
    }
}
