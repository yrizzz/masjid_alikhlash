<div>
    <x-page-hero title="Pencarian" icon="search" compact subtitle="Cari kajian, artikel, program, campaign donasi, pustaka, dan UMKM sekaligus." />

    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="relative">
            <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-4 size-5 text-muted-foreground"></i>
            <input wire:model.live.debounce.400ms="q" type="search" autofocus placeholder="Ketik kata kunci…"
                   class="h-14 w-full rounded-2xl border border-input bg-background ps-12 pe-4 text-base focus:outline-none focus:ring-2 focus:ring-ring" />
        </div>

        @php
            $groups = [
                ['Kajian', $kajians, 'book-open', fn ($i) => route('kajian.show', $i), fn ($i) => $i->title, fn ($i) => $i->ustadz],
                ['Artikel', $articles, 'newspaper', fn ($i) => route('artikel.show', $i), fn ($i) => $i->title, fn ($i) => Str::limit($i->excerpt, 70)],
                ['Campaign Donasi', $campaigns, 'hand-heart', fn ($i) => route('donasi.show', $i), fn ($i) => $i->title, fn ($i) => $i->progress.'% terkumpul'],
                ['Program', $programs, 'sparkles', fn ($i) => route('program.show', $i), fn ($i) => $i->title, fn ($i) => Str::limit($i->excerpt, 70)],
                ['E-Library', $ebooks, 'library', fn ($i) => $i->external_url ?: img_url($i->file), fn ($i) => $i->title, fn ($i) => $i->author],
                ['UMKM', $umkm, 'store', fn ($i) => route('umkm.show', $i), fn ($i) => $i->name, fn ($i) => $i->owner],
            ];
            $totalHits = collect($groups)->sum(fn ($g) => $g[1]->count());
        @endphp

        @if ($q === '')
            <div class="mt-8 rounded-2xl border border-dashed border-border p-10 text-center">
                <i data-lucide="search" class="mx-auto size-10 text-muted-foreground/40"></i>
                <p class="mt-4 text-sm text-muted-foreground">Mulai ketik untuk mencari di seluruh isi website masjid.</p>
            </div>
        @elseif ($totalHits === 0)
            <x-empty-state class="mt-8" icon="search-x" title="Tidak ada hasil" message="Tidak ditemukan apa pun untuk “{{ $q }}”." />
        @else
            <p class="mt-6 text-sm text-muted-foreground">{{ $totalHits }} hasil untuk “{{ $q }}”</p>

            <div class="mt-4 space-y-6">
                @foreach ($groups as [$label, $items, $icon, $url, $title, $sub])
                    @if ($items->isNotEmpty())
                        <section>
                            <h2 class="flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-muted-foreground">
                                <i data-lucide="{{ $icon }}" class="size-4"></i>{{ $label }}
                            </h2>
                            <div class="mt-2 divide-y divide-border overflow-hidden rounded-2xl border border-border bg-card">
                                @foreach ($items as $item)
                                    <a href="{{ $url($item) }}" class="flex items-center gap-3 p-4 transition-colors hover:bg-accent">
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate font-medium">{{ $title($item) }}</p>
                                            <p class="truncate text-xs text-muted-foreground">{{ $sub($item) }}</p>
                                        </div>
                                        <i data-lucide="chevron-right" class="size-4 shrink-0 text-muted-foreground"></i>
                                    </a>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>
