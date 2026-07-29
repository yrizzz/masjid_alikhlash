<form wire:submit="save" class="space-y-5">
    <x-ui.card title="Input Nilai TPQ" subtitle="Predikat dihitung otomatis: A ≥ 90, B ≥ 80, C ≥ 70, D ≥ 60.">
        <x-slot:actions>
            <x-ui.button type="submit" icon="save">Simpan Nilai</x-ui.button>
        </x-slot:actions>

        <div class="mb-5 flex flex-wrap gap-3">
            <div class="space-y-1">
                <label class="block text-xs font-medium text-muted-foreground">Kelas</label>
                <select wire:model.live="classId" class="h-10 rounded-lg border border-input bg-background px-3 text-sm">
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-medium text-muted-foreground">Semester</label>
                <select wire:model.live="term" class="h-10 rounded-lg border border-input bg-background px-3 text-sm">
                    <option>Ganjil</option><option>Genap</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-medium text-muted-foreground">Mata Pelajaran</label>
                <select wire:model.live="subject" class="h-10 rounded-lg border border-input bg-background px-3 text-sm">
                    @foreach ($subjects as $s)
                        <option>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="divide-y divide-border">
            @forelse ($students as $s)
                <div wire:key="gr-{{ $s->id }}" class="flex items-center gap-3 py-3">
                    <x-ui.avatar :src="$s->photo ? img_url($s->photo) : null" :name="$s->name" size="sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ $s->name }}</p>
                        <p class="text-xs text-muted-foreground">{{ $s->nis }}</p>
                    </div>
                    <input type="number" min="0" max="100" wire:model="scores.{{ $s->id }}" placeholder="0–100"
                           class="h-10 w-24 rounded-lg border border-input bg-background px-3 text-center text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-ring" />
                </div>
            @empty
                <p class="py-12 text-center text-sm text-muted-foreground">Belum ada santri aktif pada kelas ini.</p>
            @endforelse
        </div>
    </x-ui.card>
</form>
