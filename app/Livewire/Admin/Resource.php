<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Event;
use App\Support\Resources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * Komponen CRUD generik untuk seluruh modul admin.
 * Definisi kolom & field diambil dari App\Support\Resources.
 */
class Resource extends Component
{
    use WithFileUploads, WithPagination;

    public string $resource = '';

    #[Url(as: 'q', keep: false)]
    public string $search = '';

    public string $sortBy = '';
    public string $sortDir = 'desc';
    public int $perPage = 15;

    /** State form. */
    public array $form = [];
    public ?int $editingId = null;
    public bool $showForm = false;
    public ?int $confirmingDelete = null;

    /** Berkas yang diunggah, dipetakan per nama field. */
    public array $uploads = [];

    public function mount(): void
    {
        $this->resource = (string) Route::current()->defaults['resource'];
        $def = $this->def();
        $this->sortBy = $def['sort'][0] ?? 'id';
        $this->sortDir = $def['sort'][1] ?? 'desc';
    }

    protected function def(): array
    {
        return Resources::find($this->resource) ?? abort(404);
    }

    protected function modelClass(): string
    {
        return $this->def()['model'];
    }

    // ── Tabel ───────────────────────────────────────────────────────────

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sort(string $column): void
    {
        if (str_contains($column, '.')) {
            return; // kolom relasi tidak bisa diurutkan langsung
        }

        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    protected function rows()
    {
        $def = $this->def();
        $query = $this->modelClass()::query();

        if (! empty($def['with'])) {
            $query->with($def['with']);
        }
        if (! empty($def['counts'])) {
            $query->withCount($def['counts']);
        }

        if ($this->search !== '' && ! empty($def['search'])) {
            $query->where(function ($q) use ($def) {
                foreach ($def['search'] as $col) {
                    $q->orWhere($col, 'like', '%'.$this->search.'%');
                }
            });
        }

        $sortBy = $this->isRealColumn($this->sortBy) ? $this->sortBy : 'id';

        return $query->orderBy($sortBy, $this->sortDir)->paginate($this->perPage);
    }

    /** Kolom hasil accessor / relasi tidak boleh masuk ORDER BY. */
    protected function isRealColumn(string $column): bool
    {
        static $cache = [];

        $table = (new ($this->modelClass())())->getTable();
        $cache[$table] ??= \Illuminate\Support\Facades\Schema::getColumnListing($table);

        return in_array($column, $cache[$table], true);
    }

    // ── Form ────────────────────────────────────────────────────────────

    public function create(): void
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->uploads = [];
        $this->form = [];

        foreach ($this->def()['fields'] as $key => $field) {
            $this->form[$key] = $this->defaultFor($field);
        }

        $this->showForm = true;
    }

    protected function defaultFor(array $field): mixed
    {
        $default = $field['default'] ?? null;

        return match ($default) {
            'now'   => now()->format('Y-m-d\TH:i'),
            'today' => today()->format('Y-m-d'),
            'year'  => (int) now()->year,
            null    => $field['type'] === 'toggle' ? false : '',
            default => $default,
        };
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $record = $this->modelClass()::findOrFail($id);
        $this->editingId = $id;
        $this->uploads = [];
        $this->form = [];

        foreach ($this->def()['fields'] as $key => $field) {
            $value = $record->{$key};

            $this->form[$key] = match ($field['type']) {
                'datetime' => $value ? \Carbon\Carbon::parse($value)->format('Y-m-d\TH:i') : '',
                'date'     => $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : '',
                'time'     => $value ? substr((string) $value, 0, 5) : '',
                'toggle'   => (bool) $value,
                'password' => '',
                default    => $value === null ? '' : (is_array($value) ? implode(', ', $value) : $value),
            };
        }

        $this->showForm = true;
    }

    public function save(): void
    {
        $def = $this->def();
        $rules = [];

        foreach ($def['fields'] as $key => $field) {
            if (! empty($field['rules'])) {
                $rule = $field['rules'];
                // Kata sandi opsional saat mengubah data.
                if ($field['type'] === 'password' && $this->editingId) {
                    continue;
                }
                $rules['form.'.$key] = $rule;
            }
        }

        $this->validate($rules, [], $this->attributeLabels());

        $model = $this->editingId
            ? $this->modelClass()::findOrFail($this->editingId)
            : new ($this->modelClass())();

        foreach ($def['fields'] as $key => $field) {
            $value = $this->form[$key] ?? null;

            if (in_array($field['type'], ['image', 'file'], true)) {
                if (! empty($this->uploads[$key])) {
                    $model->{$key} = $this->uploads[$key]->store('uploads/'.$this->resource, 'public');
                }

                continue;
            }

            if ($field['type'] === 'password') {
                if (filled($value)) {
                    $model->{$key} = $value;
                }

                continue;
            }

            if ($field['type'] === 'slug' && blank($value)) {
                $value = Str::slug((string) ($this->form[$field['from'] ?? 'title'] ?? Str::random(8)));
            }

            $model->{$key} = match ($field['type']) {
                'toggle'          => (bool) $value,
                'number', 'money' => $value === '' || $value === null ? null : $value,
                default           => $value === '' ? null : $value,
            };
        }

        $model->save();

        $this->showForm = false;
        $this->dispatch('toast', message: $def['singular'].' berhasil disimpan.', variant: 'success');
    }

    protected function attributeLabels(): array
    {
        $out = [];
        foreach ($this->def()['fields'] as $key => $field) {
            $out['form.'.$key] = strtolower($field['label']);
        }

        return $out;
    }

    public function delete(int $id): void
    {
        $this->modelClass()::findOrFail($id)->delete();
        $this->confirmingDelete = null;
        $this->dispatch('toast', message: 'Data dihapus.', variant: 'destructive');
    }

    public function toggleField(int $id, string $field): void
    {
        $record = $this->modelClass()::findOrFail($id);
        $record->{$field} = ! $record->{$field};
        $record->save();
    }

    // ── Opsi select ─────────────────────────────────────────────────────

    /** @return array<string|int, string> */
    public function optionsFor(array $field): array
    {
        $options = $field['options'] ?? [];

        if ($options === 'event_types') {
            return collect(Event::TYPES)->map(fn ($t) => $t['label'])->all();
        }

        if (is_array($options) && ($options[0] ?? null) === 'categories') {
            return Category::type($options[1])->pluck('name', 'id')->all();
        }

        if (is_array($options) && ($options[0] ?? null) === 'model') {
            return $options[1]::orderBy($options[2])->pluck($options[2], 'id')->all();
        }

        return is_array($options) ? $options : [];
    }

    /** Ambil nilai kolom, mendukung notasi titik untuk relasi. */
    public function value(Model $row, string $column): mixed
    {
        if (! str_contains($column, '.')) {
            return $row->{$column};
        }

        [$rel, $attr] = explode('.', $column, 2);

        return $row->{$rel}?->{$attr};
    }

    public function render()
    {
        $def = $this->def();

        return view('livewire.admin.resource', [
            'def'  => $def,
            'rows' => $this->rows(),
        ])->layout('components.layouts.app', [
            'title'       => $def['title'],
            'breadcrumbs' => [['label' => 'Admin', 'href' => route('admin.dashboard')], ['label' => $def['title']]],
        ]);
    }
}
