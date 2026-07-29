@php
    $value = $this->value($row, $key);
    $type = $col['type'] ?? 'text';
    $map = $col['map'] ?? null;
    if ($map === 'event_types') {
        $map = collect(\App\Models\Event::TYPES)->map(fn ($t) => $t['label'])->all();
    }
    $label = is_array($map) ? ($map[$value] ?? $value) : $value;
@endphp

@switch($type)
    @case('image')
        <div class="size-11 shrink-0 overflow-hidden rounded-lg bg-muted">
            @if ($value)
                <img src="{{ img_url($value) }}" alt="" class="size-full object-cover" />
            @else
                <div class="grid size-full place-items-center text-muted-foreground/50"><i data-lucide="image" class="size-4"></i></div>
            @endif
        </div>
        @break

    @case('avatar')
        <x-ui.avatar :src="$value ? img_url($value) : null" :name="$row->name ?? '?'" size="sm" />
        @break

    @case('badge')
        @if (filled($value))
            <x-ui.badge variant="{{ in_array($value, ['approved','paid','active','aktif','lunas','live','selesai'], true) ? 'success' : (in_array($value, ['pending','belum','draft','scheduled'], true) ? 'warning' : (in_array($value, ['rejected','failed','expired','keluar'], true) ? 'destructive' : 'default')) }}">
                {{ $label }}
            </x-ui.badge>
        @else
            <span class="text-muted-foreground">—</span>
        @endif
        @break

    @case('money')
        <span class="font-semibold tabular-nums">{{ rupiah($value) }}</span>
        @break

    @case('number')
        <span class="tabular-nums">{{ number_format((float) $value, 0, ',', '.') }}</span>
        @break

    @case('date')
        <span class="whitespace-nowrap text-muted-foreground">{{ $value ? tanggal_id(\Carbon\Carbon::parse($value), false) : '—' }}</span>
        @break

    @case('datetime')
        <span class="whitespace-nowrap text-muted-foreground">
            {{ $value ? tanggal_id(\Carbon\Carbon::parse($value), false).' · '.\Carbon\Carbon::parse($value)->format('H:i') : '—' }}
        </span>
        @break

    @case('bool')
        <button wire:click="toggleField({{ $row->id }}, '{{ $key }}')" type="button"
                class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $value ? 'bg-success' : 'bg-muted-foreground/30' }}">
            <span class="inline-block size-3.5 rounded-full bg-white transition-transform {{ $value ? 'translate-x-[1.15rem]' : 'translate-x-1' }}"></span>
        </button>
        @break

    @case('progress')
        <div class="min-w-[7rem]">
            <div class="h-1.5 overflow-hidden rounded-full bg-muted">
                <div class="h-full rounded-full bg-primary" style="width: {{ min(100, (float) $value) }}%"></div>
            </div>
            <span class="mt-1 block text-xs text-muted-foreground">{{ (float) $value }}%</span>
        </div>
        @break

    @case('muted')
        <span class="text-muted-foreground">{{ \Illuminate\Support\Str::limit((string) $value, 40) ?: '—' }}</span>
        @break

    @default
        <div class="min-w-0">
            <p class="truncate font-medium">{{ \Illuminate\Support\Str::limit(strip_tags((string) $value), 60) ?: '—' }}</p>
            @if (! empty($col['sub']) && filled($row->{$col['sub']}))
                <p class="truncate text-xs text-muted-foreground">{{ \Illuminate\Support\Str::limit(strip_tags((string) $row->{$col['sub']}), 60) }}</p>
            @endif
        </div>
@endswitch
