<?php

namespace App\Livewire\Admin;

use App\Models\ImamSchedule;
use App\Services\PrayerTimeService;
use Livewire\Component;

class ImamBoard extends Component
{
    /** grid[hari][sholat] = ['imam' => …, 'muadzin' => …, 'backup' => …] */
    public array $grid = [];

    public function mount(): void
    {
        $existing = ImamSchedule::get()->keyBy(fn ($s) => $s->day_of_week.'-'.$s->prayer);

        foreach (array_keys(ImamSchedule::DAYS) as $day) {
            foreach (array_keys(ImamSchedule::PRAYERS) as $prayer) {
                $row = $existing->get($day.'-'.$prayer);
                $this->grid[$day][$prayer] = [
                    'imam'    => $row->imam ?? '',
                    'muadzin' => $row->muadzin ?? '',
                    'backup'  => $row->backup ?? '',
                ];
            }
        }
    }

    public function save(): void
    {
        foreach ($this->grid as $day => $prayers) {
            foreach ($prayers as $prayer => $values) {
                if (blank($values['imam'])) {
                    ImamSchedule::where('day_of_week', $day)->where('prayer', $prayer)->delete();

                    continue;
                }

                ImamSchedule::updateOrCreate(
                    ['day_of_week' => $day, 'prayer' => $prayer],
                    ['imam' => $values['imam'], 'muadzin' => $values['muadzin'] ?: null, 'backup' => $values['backup'] ?: null],
                );
            }
        }

        $this->dispatch('toast', message: 'Jadwal imam & muadzin disimpan.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.admin.imam', [
            'days'    => ImamSchedule::DAYS,
            'prayers' => ImamSchedule::PRAYERS,
            'times'   => app(PrayerTimeService::class)->fardhForDate(),
        ])->layout('components.layouts.app', [
            'title'       => 'Jadwal Imam & Muadzin',
            'breadcrumbs' => [['label' => 'Ibadah'], ['label' => 'Jadwal Imam']],
        ]);
    }
}
