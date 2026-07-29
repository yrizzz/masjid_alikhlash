<div>
    <x-page-hero title="Jadwal Khatib Jumat" icon="mic-vocal"
                 subtitle="Tema khutbah, khatib, imam, dan muadzin setiap pekan." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.3fr_1fr]">
            <div>
                @if ($next)
                    <div class="overflow-hidden rounded-3xl border border-border bg-card">
                        <div class="grid sm:grid-cols-[auto_1fr]">
                            <div class="bg-gradient-to-br from-primary to-teal-700 p-6 text-center text-white sm:w-40">
                                <p class="text-xs uppercase tracking-wider text-white/70">Jumat</p>
                                <p class="mt-1 text-5xl font-bold">{{ $next->date->format('d') }}</p>
                                <p class="text-sm">{{ $next->date->translatedFormat('M Y') }}</p>
                                <p class="mt-3 text-xs text-white/80">
                                    {{ $next->date->isToday() ? 'Hari ini' : $next->date->diffForHumans() }}
                                </p>
                            </div>
                            <div class="p-6">
                                <span class="text-xs font-semibold uppercase tracking-wider text-primary">Tema Khutbah</span>
                                <h2 class="mt-1.5 text-xl font-bold leading-snug">{{ $next->theme }}</h2>

                                <dl class="mt-5 grid gap-3 sm:grid-cols-3">
                                    @foreach ([['Khatib', $next->khatib, 'mic-vocal'], ['Imam', $next->imam, 'user-check'], ['Muadzin', $next->muadzin, 'volume-2']] as [$l, $v, $ic])
                                        <div>
                                            <dt class="flex items-center gap-1.5 text-xs text-muted-foreground"><i data-lucide="{{ $ic }}" class="size-3.5"></i>{{ $l }}</dt>
                                            <dd class="mt-0.5 text-sm font-semibold">{{ $v ?: '—' }}</dd>
                                        </div>
                                    @endforeach
                                </dl>

                                @if ($next->summary)
                                    <p class="mt-4 text-sm leading-relaxed text-muted-foreground">{{ $next->summary }}</p>
                                @endif

                                @if ($next->attachment)
                                    <a href="{{ img_url($next->attachment) }}" target="_blank" class="mt-4 inline-block">
                                        <x-ui.button size="sm" variant="outline" icon="download">Unduh Naskah PDF</x-ui.button>
                                    </a>
                                @endif
                            </div>
                        </div>
                        @if ($next->poster)
                            <img src="{{ img_url($next->poster) }}" alt="Poster khutbah" class="w-full object-cover" />
                        @endif
                    </div>
                @else
                    <x-empty-state icon="mic-vocal" title="Belum ada jadwal khatib" message="Pengurus belum mengisi jadwal Jumat mendatang." />
                @endif

                {{-- Arsip --}}
                <h2 class="mt-10 text-lg font-bold">Arsip Khutbah</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($past as $j)
                        <div class="flex items-start gap-4 rounded-2xl border border-border bg-card p-4">
                            <div class="grid size-12 shrink-0 place-items-center rounded-xl bg-muted text-center">
                                <div>
                                    <p class="text-[0.6rem] uppercase leading-none text-muted-foreground">{{ $j->date->translatedFormat('M') }}</p>
                                    <p class="text-sm font-bold">{{ $j->date->format('d') }}</p>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium">{{ $j->theme }}</p>
                                <p class="text-sm text-muted-foreground">{{ $j->khatib }}</p>
                            </div>
                            @if ($j->attachment)
                                <a href="{{ img_url($j->attachment) }}" target="_blank" class="shrink-0 rounded-lg p-2 text-muted-foreground hover:bg-accent">
                                    <i data-lucide="file-down" class="size-4"></i>
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-muted-foreground">Belum ada arsip.</p>
                    @endforelse
                </div>

                @if ($past->hasPages())
                    <div class="mt-5">{{ $past->links() }}</div>
                @endif
            </div>

            <div>
                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-bold">Jadwal Berikutnya</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($upcoming as $j)
                            <div class="flex gap-3">
                                <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-center text-primary">
                                    <div>
                                        <p class="text-[0.6rem] uppercase leading-none">{{ $j->date->translatedFormat('M') }}</p>
                                        <p class="text-sm font-bold">{{ $j->date->format('d') }}</p>
                                    </div>
                                </div>
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-medium">{{ $j->theme }}</p>
                                    <p class="truncate text-xs text-muted-foreground">{{ $j->khatib }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">Belum dijadwalkan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
