<div>
    <x-page-hero title="Kalender & Agenda" icon="calendar-days"
                 subtitle="Hari besar Islam, kajian, pengajian, rapat, TPQ, dan kerja bakti dalam satu kalender." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
            <div>
                <div class="overflow-hidden rounded-2xl border border-border bg-card">
                    <div class="flex items-center justify-between gap-3 border-b border-border px-5 py-4">
                        <h2 class="text-lg font-bold">{{ $label }}</h2>
                        <div class="flex gap-1">
                            <button wire:click="shift(-1)" class="rounded-lg border border-border p-2 hover:bg-accent"><i data-lucide="chevron-left" class="size-4"></i></button>
                            <button wire:click="shift(1)" class="rounded-lg border border-border p-2 hover:bg-accent"><i data-lucide="chevron-right" class="size-4"></i></button>
                        </div>
                    </div>

                    <div class="grid grid-cols-7 border-b border-border bg-muted/40 text-center text-xs font-semibold uppercase text-muted-foreground">
                        @foreach (['Ahad', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $d)
                            <div class="py-2.5">{{ $d }}</div>
                        @endforeach
                    </div>

                    <div class="grid grid-cols-7">
                        @foreach ($cells as $cell)
                            <div class="min-h-[5.5rem] border-b border-e border-border p-1.5 last:border-e-0
                                        {{ $cell['inMonth'] ? '' : 'bg-muted/25' }}
                                        {{ $cell['isToday'] ? 'bg-primary/8 ring-1 ring-inset ring-primary/30' : '' }}">
                                <div class="flex items-start justify-between">
                                    <span class="text-sm font-semibold {{ $cell['inMonth'] ? '' : 'text-muted-foreground/50' }} {{ $cell['isToday'] ? 'grid size-6 place-items-center rounded-full bg-primary text-primary-foreground' : '' }}">
                                        {{ $cell['date']->day }}
                                    </span>
                                    <span class="text-[0.6rem] text-muted-foreground">{{ $cell['hijri']['day'] }}</span>
                                </div>

                                @if ($cell['holiday'])
                                    <p class="mt-1 truncate rounded bg-amber-500/15 px-1 py-0.5 text-[0.6rem] font-semibold text-amber-700 dark:text-amber-400"
                                       title="{{ $cell['holiday'] }}">{{ $cell['holiday'] }}</p>
                                @endif

                                @foreach ($cell['events']->take(2) as $e)
                                    <p class="mt-1 truncate rounded px-1 py-0.5 text-[0.6rem] font-medium"
                                       style="background: {{ $e->type_color }}1f; color: {{ $e->type_color }}"
                                       title="{{ $e->title }}">{{ $e->title }}</p>
                                @endforeach

                                @if ($cell['events']->count() > 2)
                                    <p class="mt-0.5 text-[0.6rem] text-muted-foreground">+{{ $cell['events']->count() - 2 }} lagi</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Legenda --}}
                <div class="mt-4 flex flex-wrap gap-3 text-xs">
                    @foreach (\App\Models\Event::TYPES as $type)
                        <span class="flex items-center gap-1.5">
                            <span class="size-2.5 rounded-full" style="background: {{ $type['color'] }}"></span>{{ $type['label'] }}
                        </span>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-5">
                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Agenda Terdekat</h2>
                    <div class="mt-4 space-y-3.5">
                        @forelse ($upcoming as $e)
                            <div class="flex gap-3">
                                <div class="grid size-11 shrink-0 place-items-center rounded-xl text-center"
                                     style="background: {{ $e->type_color }}1a; color: {{ $e->type_color }}">
                                    <div>
                                        <p class="text-[0.6rem] uppercase leading-none">{{ $e->start_at->translatedFormat('M') }}</p>
                                        <p class="text-sm font-bold">{{ $e->start_at->format('d') }}</p>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium leading-snug">{{ $e->title }}</p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ $e->type_label }} · {{ $e->all_day ? 'Sepanjang hari' : $e->start_at->format('H:i') }}
                                    </p>
                                    @if ($e->location)<p class="text-xs text-muted-foreground">{{ $e->location }}</p>@endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">Belum ada agenda mendatang.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Hari Besar Islam</h2>
                    <p class="text-xs text-muted-foreground">Setahun ke depan</p>
                    <div class="mt-4 space-y-2.5">
                        @foreach (array_slice($holidays, 0, 10) as $h)
                            <div class="flex items-baseline justify-between gap-3 text-sm">
                                <span class="min-w-0 truncate">{{ $h['name'] }}</span>
                                <span class="shrink-0 text-xs text-muted-foreground">{{ tanggal_id($h['date'], false) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
