<div>
    <x-page-hero title="Artikel" icon="newspaper" subtitle="Kabar masjid, tausiyah singkat, dan wawasan keislaman untuk jamaah." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_18rem] w-full min-w-0">
            <div class="w-full min-w-0">
                @if ($featured && $search === '' && $kategori === '')
                    <a href="{{ route('artikel.show', $featured) }}" wire:navigate
                       class="group grid overflow-hidden rounded-2xl border border-border bg-card sm:grid-cols-2 card-transition w-full">
                        <div class="aspect-[16/10] overflow-hidden bg-muted sm:aspect-auto">
                            <img src="{{ img_url($featured->cover, $featured->slug) }}" alt=""
                                 class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </div>
                        <div class="flex flex-col justify-center p-6">
                            <x-ui.badge class="self-start bg-amber-500 text-stone-950 font-bold">Artikel Pilihan</x-ui.badge>
                            <h2 class="mt-3 text-xl font-bold leading-snug text-foreground group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $featured->title }}</h2>
                            <p class="mt-2 line-clamp-3 text-sm text-muted-foreground">{{ $featured->excerpt }}</p>
                            <p class="mt-4 flex items-center gap-3 text-xs text-muted-foreground font-medium">
                                <span>{{ tanggal_id($featured->published_at, false) }}</span>
                                <span class="flex items-center gap-1"><i data-lucide="clock" class="size-3 text-amber-500"></i>{{ $featured->reading_time }} mnt</span>
                            </p>
                        </div>
                    </a>
                @endif

                <div class="mt-6 flex flex-col gap-2.5 sm:flex-row sm:items-center">
                    <div class="relative w-full sm:flex-1">
                        <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground"></i>
                        <input wire:model.live.debounce.400ms="search" type="search" placeholder="Cari artikel…"
                               class="h-11 w-full rounded-xl border border-input bg-background ps-10 pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50" />
                    </div>
                    <select wire:model.live="kategori" class="h-11 w-full sm:w-auto min-w-0 max-w-full truncate rounded-xl border border-input bg-background px-3 text-sm">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->slug }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 w-full min-w-0">
                    @forelse ($articles as $a)
                        <a href="{{ route('artikel.show', $a) }}" wire:navigate
                           class="group flex flex-col overflow-hidden rounded-2xl border border-border bg-card card-transition">
                            <div class="aspect-[16/10] overflow-hidden bg-muted">
                                <img src="{{ img_url($a->cover, $a->slug) }}" alt="{{ $a->title }}"
                                     class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                @if ($a->category)<span class="text-xs font-bold text-amber-600 dark:text-amber-400">{{ $a->category->name }}</span>@endif
                                <h3 class="mt-1 line-clamp-2 font-bold leading-snug text-foreground group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $a->title }}</h3>
                                <p class="mt-1.5 line-clamp-2 flex-1 text-sm text-muted-foreground">{{ $a->excerpt }}</p>
                                <p class="mt-3 flex items-center gap-3 text-xs text-muted-foreground font-medium">
                                    <span>{{ tanggal_id($a->published_at, false) }}</span>
                                    <span class="flex items-center gap-1"><i data-lucide="clock" class="size-3 text-amber-500"></i>{{ $a->reading_time }} mnt</span>
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="sm:col-span-2">
                            <x-empty-state icon="newspaper" title="Artikel tidak ditemukan" />
                        </div>
                    @endforelse
                </div>

                @if ($articles->hasPages())
                    <div class="mt-8">{{ $articles->links() }}</div>
                @endif
            </div>

            <aside class="space-y-5">
                <div class="rounded-2xl border border-border bg-card p-5 card-lift-sm">
                    <h2 class="font-bold text-foreground">Paling Banyak Dibaca</h2>
                    <div class="mt-4 space-y-3.5">
                        @forelse ($popular as $i => $p)
                            <a href="{{ route('artikel.show', $p) }}" wire:navigate class="flex gap-3 items-center group">
                                <span class="text-lg font-extrabold font-outfit text-amber-500/50 group-hover:text-amber-500 transition-colors">{{ $i + 1 }}</span>
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-2 text-sm font-bold leading-snug text-foreground group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $p->title }}</p>
                                    <p class="mt-0.5 text-xs text-muted-foreground font-medium">{{ number_format($p->views) }}x dibaca</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-sm text-muted-foreground">Belum ada artikel.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
