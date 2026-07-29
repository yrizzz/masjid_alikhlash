<div>
    <x-page-hero title="Program Masjid" icon="sparkles"
                 subtitle="Ramadhan, Qurban, Zakat, TPQ, Remaja Masjid, hingga Bakti Sosial." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @forelse ($types as $key => $label)
            @php $items = $programs[$key] ?? collect(); @endphp
            @if ($items->isNotEmpty())
                <section class="mb-10">
                    <h2 class="text-lg font-bold tracking-tight">{{ $label }}</h2>
                    <div class="mt-4 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($items as $p)
                            <a href="{{ route('program.show', $p) }}" wire:navigate
                               class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-1 hover:shadow-lg">
                                <div class="aspect-[16/9] overflow-hidden bg-muted">
                                    <img src="{{ img_url($p->cover, $p->slug) }}" alt="{{ $p->title }}"
                                         class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                </div>
                                <div class="p-5">
                                    <div class="flex items-center gap-2">
                                        <span class="grid size-8 place-items-center rounded-lg bg-primary/10 text-primary">
                                            <i data-lucide="{{ $p->icon }}" class="size-4"></i>
                                        </span>
                                        <x-ui.badge :variant="$p->status === 'active' ? 'success' : 'muted'">
                                            {{ $p->status === 'active' ? 'Berjalan' : 'Selesai' }}
                                        </x-ui.badge>
                                    </div>
                                    <h3 class="mt-3 font-semibold leading-snug">{{ $p->title }}</h3>
                                    <p class="mt-1.5 line-clamp-2 text-sm text-muted-foreground">{{ $p->excerpt }}</p>
                                    @if ($p->start_date)
                                        <p class="mt-3 flex items-center gap-1.5 text-xs text-muted-foreground">
                                            <i data-lucide="calendar" class="size-3.5"></i>
                                            {{ tanggal_id($p->start_date, false) }}
                                            @if ($p->end_date) – {{ tanggal_id($p->end_date, false) }} @endif
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        @empty
        @endforelse

        @if ($programs->isEmpty())
            <x-empty-state icon="sparkles" title="Belum ada program" message="Pengurus belum menambahkan program masjid." />
        @endif
    </div>
</div>
