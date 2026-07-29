<?php

namespace App\Livewire\Pub;

use App\Models\Room;
use App\Models\RoomBooking;
use Livewire\Component;

class Booking extends Component
{
    public ?int $roomId = null;
    public string $name = '';
    public string $phone = '';
    public string $purpose = '';
    public string $date = '';
    public string $startTime = '08:00';
    public string $endTime = '12:00';
    public int $participants = 0;
    public string $note = '';

    public ?RoomBooking $created = null;

    public function mount(): void
    {
        $this->date   = today()->addDays(3)->format('Y-m-d');
        $this->roomId = Room::where('is_active', true)->value('id');

        if ($user = auth()->user()) {
            $this->name  = $user->name;
            $this->phone = (string) $user->phone;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'roomId'    => 'required|exists:rooms,id',
            'name'      => 'required|min:3',
            'phone'     => 'required|min:8',
            'purpose'   => 'required|min:3',
            'date'      => 'required|date|after_or_equal:today',
            'startTime' => 'required',
            'endTime'   => 'required',
        ], [], [
            'roomId' => 'ruangan', 'name' => 'nama', 'phone' => 'nomor WhatsApp',
            'purpose' => 'keperluan', 'date' => 'tanggal', 'startTime' => 'jam mulai', 'endTime' => 'jam selesai',
        ]);

        if ($this->endTime <= $this->startTime) {
            $this->addError('endTime', 'Jam selesai harus setelah jam mulai.');

            return;
        }

        // Cegah tabrakan dengan booking yang sudah disetujui.
        $clash = RoomBooking::where('room_id', $this->roomId)
            ->whereDate('date', $this->date)
            ->whereIn('status', ['approved', 'pending'])
            ->where(fn ($q) => $q->whereBetween('start_time', [$this->startTime, $this->endTime])
                ->orWhereBetween('end_time', [$this->startTime, $this->endTime])
                ->orWhere(fn ($w) => $w->where('start_time', '<=', $this->startTime)->where('end_time', '>=', $this->endTime)))
            ->exists();

        if ($clash) {
            $this->addError('startTime', 'Jadwal bentrok dengan pemesanan lain. Silakan pilih waktu berbeda.');

            return;
        }

        $this->created = RoomBooking::create([
            'room_id'      => $this->roomId,
            'user_id'      => auth()->id(),
            'name'         => $this->name,
            'phone'        => $this->phone,
            'purpose'      => $this->purpose,
            'date'         => $this->date,
            'start_time'   => $this->startTime,
            'end_time'     => $this->endTime,
            'participants' => $this->participants,
            'note'         => $this->note ?: null,
            'status'       => 'pending',
        ]);

        $this->dispatch('toast', message: 'Permohonan terkirim. Menunggu persetujuan takmir.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.pub.booking', [
            'rooms'    => Room::where('is_active', true)->get(),
            'upcoming' => RoomBooking::with('room')->approved()
                ->whereDate('date', '>=', today())->orderBy('date')->orderBy('start_time')->take(12)->get(),
        ])->layout('components.layouts.public', ['title' => 'Booking Ruangan']);
    }
}
