<form wire:submit="save" class="space-y-5">
    <x-ui.card title="Absensi Santri TPQ" subtitle="Pilih kelas dan tanggal, lalu tandai kehadiran.">
        <x-slot:actions>
            <x-ui.button type="submit" icon="save">Simpan Absensi</x-ui.button>
        </x-slot:actions>

        <div class="mb-5 flex flex-wrap gap-3">
            <div class="space-y-1">
                <label class="block text-xs font-medium text-muted-foreground">Kelas</label>
                <select wire:model.live="classId" class="h-10 rounded-lg border border-input bg-background px-3 text-sm">
                    @foreach ($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} — {{ $c->teacher }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-medium text-muted-foreground">Tanggal</label>
                <input type="date" wire:model.live="date" class="h-10 rounded-lg border border-input bg-background px-3 text-sm" />
            </div>
        </div>

        <div class="divide-y divide-border">
            @forelse ($students as $s)
                <div wire:key="st-{{ $s->id }}" class="flex flex-wrap items-center gap-3 py-3">
                    <x-ui.avatar :src="$s->photo ? img_url($s->photo) : null" :name="$s->name" size="sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ $s->name }}</p>
                        <p class="text-xs text-muted-foreground">{{ $s->nis }}</p>
                    </div>
                    <div class="flex gap-1 rounded-lg bg-muted p-1">
                        @foreach ($statuses as $key => $label)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="marks.{{ $s->id }}" value="{{ $key }}" class="peer sr-only" />
                                <span class="block rounded-md px-3 py-1.5 text-xs font-semibold text-muted-foreground transition-colors peer-checked:bg-background peer-checked:text-foreground peer-checked:shadow-sm">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="py-12 text-center text-sm text-muted-foreground">Belum ada santri aktif pada kelas ini.</p>
            @endforelse
        </div>
    </x-ui.card>
</form>
