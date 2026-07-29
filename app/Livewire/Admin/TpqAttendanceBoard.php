<?php

namespace App\Livewire\Admin;

use App\Models\TpqAttendance;
use App\Models\TpqClass;
use App\Models\TpqStudent;
use Livewire\Component;

class TpqAttendanceBoard extends Component
{
    public ?int $classId = null;
    public string $date = '';

    /** marks[student_id] = hadir|izin|sakit|alpa */
    public array $marks = [];

    public const STATUSES = ['hadir' => 'Hadir', 'izin' => 'Izin', 'sakit' => 'Sakit', 'alpa' => 'Alpa'];

    public function mount(): void
    {
        $this->date    = today()->format('Y-m-d');
        $this->classId = TpqClass::where('is_active', true)->value('id');
        $this->loadMarks();
    }

    public function updatedClassId(): void { $this->loadMarks(); }
    public function updatedDate(): void { $this->loadMarks(); }

    protected function loadMarks(): void
    {
        $this->marks = [];
        foreach ($this->students() as $student) {
            $this->marks[$student->id] = TpqAttendance::where('tpq_student_id', $student->id)
                ->whereDate('date', $this->date)->value('status') ?? 'hadir';
        }
    }

    public function students()
    {
        return $this->classId
            ? TpqStudent::where('tpq_class_id', $this->classId)->where('status', 'aktif')->orderBy('name')->get()
            : collect();
    }

    public function save(): void
    {
        foreach ($this->marks as $studentId => $status) {
            TpqAttendance::updateOrCreate(
                ['tpq_student_id' => $studentId, 'date' => $this->date],
                ['status' => $status],
            );
        }

        $this->dispatch('toast', message: 'Absensi tersimpan.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.admin.tpq-attendance', [
            'classes'  => TpqClass::where('is_active', true)->orderBy('name')->get(),
            'students' => $this->students(),
            'statuses' => self::STATUSES,
        ])->layout('components.layouts.app', [
            'title'       => 'Absensi TPQ',
            'breadcrumbs' => [['label' => 'TPQ'], ['label' => 'Absensi']],
        ]);
    }
}
