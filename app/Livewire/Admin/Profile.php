<?php

namespace App\Livewire\Admin;

use App\Models\Facility;
use App\Models\Milestone;
use App\Models\Setting;
use Livewire\Component;

class Profile extends Component
{
    /** Field profil masjid yang tersimpan di tabel settings. */
    public const FIELDS = [
        'name'       => ['Nama Masjid', 'text'],
        'tagline'    => ['Tagline', 'text'],
        'address'    => ['Alamat Lengkap', 'textarea'],
        'phone'      => ['Telepon / WA', 'text'],
        'email'      => ['Email', 'text'],
        'maps_url'   => ['Tautan Google Maps', 'text'],
        'mosque_lat' => ['Latitude', 'text'],
        'mosque_lng' => ['Longitude', 'text'],
        'founded'    => ['Tahun Berdiri', 'text'],
        'land_area'  => ['Luas Tanah (m²)', 'text'],
        'capacity'   => ['Kapasitas Jamaah', 'text'],
        'legality'   => ['Legalitas / Nomor ID Masjid', 'text'],
        'history'    => ['Sejarah Masjid', 'editor'],
        'vision'     => ['Visi', 'textarea'],
        'mission'    => ['Misi (satu per baris)', 'textarea'],
        'virtual_tour' => ['URL Virtual Tour 360°', 'text'],
    ];

    public array $form = [];

    // Milestone state
    public bool $showMilestoneModal = false;
    public array $milestoneForm = ['id' => null, 'year' => '', 'title' => '', 'icon' => 'sparkles', 'description' => ''];

    // Facility state
    public bool $showFacilityModal = false;
    public array $facilityForm = ['id' => null, 'name' => '', 'icon' => 'building-2', 'order' => 0, 'description' => ''];

    public function mount(): void
    {
        foreach (array_keys(self::FIELDS) as $key) {
            $this->form[$key] = (string) Setting::get($key, config('masjid.'.$key, ''));
        }
    }

    public function save(): void
    {
        foreach ($this->form as $key => $value) {
            Setting::put($key, $value, 'profil');
        }

        $this->dispatch('toast', message: 'Profil masjid diperbarui.', variant: 'success');
    }

    // ── Milestone CRUD ──────────────────────────────────────────────
    public function openMilestoneModal(?int $id = null): void
    {
        $this->resetValidation();
        if ($id) {
            $m = Milestone::findOrFail($id);
            $this->milestoneForm = [
                'id'          => $m->id,
                'year'        => $m->year,
                'title'       => $m->title,
                'icon'        => $m->icon ?? 'sparkles',
                'description' => $m->description ?? '',
            ];
        } else {
            $this->milestoneForm = ['id' => null, 'year' => date('Y'), 'title' => '', 'icon' => 'sparkles', 'description' => ''];
        }
        $this->showMilestoneModal = true;
    }

    public function saveMilestone(): void
    {
        $this->validate([
            'milestoneForm.year'  => 'required',
            'milestoneForm.title' => 'required|string|max:255',
        ]);

        Milestone::updateOrCreate(
            ['id' => $this->milestoneForm['id']],
            [
                'year'        => $this->milestoneForm['year'],
                'title'       => $this->milestoneForm['title'],
                'icon'        => $this->milestoneForm['icon'] ?: 'sparkles',
                'description' => $this->milestoneForm['description'] ?: null,
            ]
        );

        $this->showMilestoneModal = false;
        $this->dispatch('toast', message: 'Timeline perkembangan berhasil disimpan.', variant: 'success');
    }

    public function deleteMilestone(int $id): void
    {
        Milestone::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Timeline dihapus.', variant: 'destructive');
    }

    // ── Facility CRUD ───────────────────────────────────────────────
    public function openFacilityModal(?int $id = null): void
    {
        $this->resetValidation();
        if ($id) {
            $f = Facility::findOrFail($id);
            $this->facilityForm = [
                'id'          => $f->id,
                'name'        => $f->name,
                'icon'        => $f->icon ?? 'building-2',
                'order'       => $f->order ?? 0,
                'description' => $f->description ?? '',
            ];
        } else {
            $this->facilityForm = ['id' => null, 'name' => '', 'icon' => 'building-2', 'order' => 0, 'description' => ''];
        }
        $this->showFacilityModal = true;
    }

    public function saveFacility(): void
    {
        $this->validate([
            'facilityForm.name' => 'required|string|max:255',
        ]);

        Facility::updateOrCreate(
            ['id' => $this->facilityForm['id']],
            [
                'name'        => $this->facilityForm['name'],
                'icon'        => $this->facilityForm['icon'] ?: 'building-2',
                'order'       => (int) ($this->facilityForm['order'] ?? 0),
                'description' => $this->facilityForm['description'] ?: null,
            ]
        );

        $this->showFacilityModal = false;
        $this->dispatch('toast', message: 'Fasilitas berhasil disimpan.', variant: 'success');
    }

    public function deleteFacility(int $id): void
    {
        Facility::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Fasilitas dihapus.', variant: 'destructive');
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'fields'     => self::FIELDS,
            'milestones' => Milestone::orderBy('year')->get(),
            'facilities' => Facility::orderBy('order')->get(),
        ])->layout('components.layouts.app', [
            'title'       => 'Profil Masjid',
            'breadcrumbs' => [['label' => 'Master Data'], ['label' => 'Profil Masjid']],
        ]);
    }
}
