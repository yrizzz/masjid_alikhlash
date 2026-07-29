<?php

namespace App\Livewire\Admin;

use App\Models\TpqClass;
use App\Models\TpqGrade;
use App\Models\TpqStudent;
use Livewire\Component;

class TpqGradeBoard extends Component
{
    public ?int $classId = null;
    public string $term = 'Ganjil';
    public string $subject = 'Tahsin';

    /** scores[student_id] = 0..100 */
    public array $scores = [];

    public const SUBJECTS = ['Tahsin', 'Tahfidz', 'Fiqih', 'Akhlak', 'Praktik Ibadah'];

    public function mount(): void
    {
        $this->classId = TpqClass::where('is_active', true)->value('id');
        $this->loadScores();
    }

    public function updatedClassId(): void { $this->loadScores(); }
    public function updatedTerm(): void { $this->loadScores(); }
    public function updatedSubject(): void { $this->loadScores(); }

    protected function loadScores(): void
    {
        $this->scores = [];
        foreach ($this->students() as $student) {
            $this->scores[$student->id] = (string) (TpqGrade::where('tpq_student_id', $student->id)
                ->where('term', $this->term)->where('subject', $this->subject)->value('score') ?? '');
        }
    }

    public function students()
    {
        return $this->classId
            ? TpqStudent::where('tpq_class_id', $this->classId)->where('status', 'aktif')->orderBy('name')->get()
            : collect();
    }

    public static function predicate(int $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default      => 'E',
        };
    }

    public function save(): void
    {
        foreach ($this->scores as $studentId => $score) {
            if ($score === '' || $score === null) {
                continue;
            }

            TpqGrade::updateOrCreate(
                ['tpq_student_id' => $studentId, 'term' => $this->term, 'subject' => $this->subject],
                ['score' => (int) $score, 'predicate' => static::predicate((int) $score)],
            );
        }

        $this->dispatch('toast', message: 'Nilai tersimpan.', variant: 'success');
    }

    public function render()
    {
        return view('livewire.admin.tpq-grades', [
            'classes'  => TpqClass::where('is_active', true)->orderBy('name')->get(),
            'students' => $this->students(),
            'subjects' => self::SUBJECTS,
        ])->layout('components.layouts.app', [
            'title'       => 'Nilai TPQ',
            'breadcrumbs' => [['label' => 'TPQ'], ['label' => 'Nilai']],
        ]);
    }
}
