<?php

namespace App\Livewire\Pub;

use App\Models\Event;
use App\Services\HijriService;
use Carbon\Carbon;
use Livewire\Component;

class Kalender extends Component
{
    public int $year;
    public int $month;

    public function mount(): void
    {
        $this->year  = (int) now()->year;
        $this->month = (int) now()->month;
    }

    public function shift(int $delta): void
    {
        $d = Carbon::create($this->year, $this->month, 1)->addMonths($delta);
        $this->year  = $d->year;
        $this->month = $d->month;
    }

    public function render()
    {
        $start = Carbon::create($this->year, $this->month, 1);
        $end   = $start->copy()->endOfMonth();
        $hijri = app(HijriService::class);

        $events = Event::where('is_public', true)
            ->whereBetween('start_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->orderBy('start_at')->get()->groupBy(fn ($e) => $e->start_at->day);

        $holidays = collect($hijri->holidaysBetween($start, $end))->groupBy(fn ($h) => $h['date']->day);

        // Matriks kalender: mulai Ahad.
        $cells = [];
        $cursor = $start->copy()->startOfWeek(Carbon::SUNDAY);
        $last   = $end->copy()->endOfWeek(Carbon::SATURDAY);

        while ($cursor->lessThanOrEqualTo($last)) {
            $cells[] = [
                'date'      => $cursor->copy(),
                'inMonth'   => $cursor->month === $this->month,
                'isToday'   => $cursor->isToday(),
                'hijri'     => $hijri->convert($cursor),
                'events'    => $cursor->month === $this->month ? ($events[$cursor->day] ?? collect()) : collect(),
                'holiday'   => $cursor->month === $this->month ? ($holidays[$cursor->day][0]['name'] ?? null) : null,
            ];
            $cursor->addDay();
        }

        return view('livewire.pub.kalender', [
            'cells'    => $cells,
            'label'    => $start->translatedFormat('F Y'),
            'upcoming' => Event::where('is_public', true)->upcoming()->take(8)->get(),
            'holidays' => $hijri->holidaysBetween(today(), today()->addYear()),
        ])->layout('components.layouts.public', ['title' => 'Kalender & Agenda']);
    }
}
