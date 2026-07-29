<div>
    <x-page-hero title="Kajian" icon="book-open"
                  subtitle="Video, rekaman audio, poster, dan materi PDF dari majelis ilmu Masjid Al-Ikhlash." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-4 sm:py-10 sm:px-6 lg:px-8 overflow-x-hidden">
        <div class="grid gap-6 sm:gap-8 lg:grid-cols-[1fr_18rem] w-full min-w-0">
            <div class="w-full min-w-0">
                {{-- Minimalist Search & Filter Bar --}}
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center w-full min-w-0">
                    <div class="relative w-full sm:flex-1 min-w-0">
                        <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground"></i>
                        <input wire:model.live.debounce.400ms="search" type="search" placeholder="Cari kajian atau ustadz…"
                               class="h-10 w-full rounded-xl border border-input bg-background ps-9 pe-3 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50" />
                    </div>
                    <div class="flex gap-2 w-full sm:w-auto min-w-0">
                        <select wire:model.live="media" class="h-10 flex-1 sm:w-32 min-w-0 max-w-full truncate rounded-xl border border-input bg-background px-2.5 text-xs focus:outline-none">
                            <option value="">Semua media</option>
                            <option value="video">Video</option><option value="audio">Audio</option>
                            <option value="pdf">PDF</option><option value="slide">Slide</option>
                        </select>
                        <select wire:model.live="ustadz" class="h-10 flex-1 sm:w-36 min-w-0 max-w-full truncate rounded-xl border border-input bg-background px-2.5 text-xs focus:outline-none">
                            <option value="">Semua ustadz</option>
                            @foreach ($ustadzList as $u)
                                <option value="{{ $u }}">{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if ($categories->isNotEmpty())
                    <div class="mt-2.5 flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none max-w-full">
                        <button wire:click="$set('kategori', '')"
                                class="shrink-0 rounded-full px-3 py-1 text-xs font-medium transition-colors {{ $kategori === '' ? 'bg-primary text-primary-foreground font-bold' : 'bg-muted text-muted-foreground hover:text-foreground' }}">
                            Semua kategori
                        </button>
                        @foreach ($categories as $c)
                            <button wire:click="$set('kategori', '{{ $c->slug }}')"
                                    class="shrink-0 rounded-full px-3 py-1 text-xs font-medium transition-colors {{ $kategori === $c->slug ? 'bg-primary text-primary-foreground font-bold' : 'bg-muted text-muted-foreground hover:text-foreground' }}">
                                {{ $c->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                {{-- Minimalist High-Density List Cards --}}
                <div class="mt-5 grid grid-cols-1 gap-3 sm:gap-5 sm:grid-cols-2 xl:grid-cols-3 w-full min-w-0">
                    @forelse ($kajians as $k)
                        <a href="{{ route('kajian.show', $k) }}" wire:navigate
                           class="group flex flex-row sm:flex-col overflow-hidden rounded-2xl border border-border bg-card card-transition hover:border-amber-500/50 shadow-sm w-full p-2.5 sm:p-0">
                            {{-- Poster Thumbnail --}}
                            <div class="relative w-24 xs:w-28 sm:w-full aspect-[4/3] sm:aspect-[16/10] shrink-0 overflow-hidden rounded-xl sm:rounded-none bg-muted">
                                <img src="{{ img_url($k->poster, $k->slug) }}" alt="{{ $k->title }}"
                                     class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                @if ($k->media_type !== 'none')
                                    <span class="absolute start-1 top-1 sm:start-3 sm:top-3 inline-flex items-center gap-1 rounded-full bg-black/75 px-1.5 py-0.5 sm:px-2.5 sm:py-1 text-[0.6rem] sm:text-xs font-semibold text-white backdrop-blur">
                                        <i data-lucide="{{ ['video' => 'play', 'audio' => 'headphones', 'pdf' => 'file-text', 'slide' => 'presentation'][$k->media_type] ?? 'file' }}" class="size-2.5 sm:size-3"></i>
                                        <span class="hidden xs:inline sm:inline">{{ strtoupper($k->media_type) }}</span>
                                    </span>
                                @endif
                                @if ($k->is_today)
                                    <span class="absolute end-1 top-1 sm:end-3 sm:top-3 rounded-full bg-amber-500 px-1.5 py-0.5 sm:px-2.5 sm:py-1 text-[0.6rem] sm:text-xs font-bold text-stone-950">HARI INI</span>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex flex-1 flex-col justify-between ps-3 sm:ps-0 sm:p-4 min-w-0">
                                <div>
                                    <div class="flex items-center justify-between gap-1">
                                        @if ($k->category)
                                            <span class="text-[0.65rem] sm:text-xs font-bold text-amber-600 dark:text-amber-400 truncate">{{ $k->category->name }}</span>
                                        @endif
                                        @if ($k->start_at)
                                            <span class="text-[0.65rem] sm:text-xs font-medium text-muted-foreground shrink-0 sm:hidden">{{ $k->start_at->translatedFormat('d M') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="mt-0.5 sm:mt-1 text-xs xs:text-sm sm:text-base font-bold leading-snug text-foreground group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors line-clamp-2">
                                        {{ $k->title }}
                                    </h3>
                                </div>

                                <div class="mt-2 flex items-center justify-between text-[0.68rem] sm:text-xs text-muted-foreground font-medium border-t border-border/40 sm:border-0 pt-1.5 sm:pt-0">
                                    <span class="flex items-center gap-1 truncate">
                                        <i data-lucide="user-round" class="size-3 sm:size-3.5 text-amber-500 shrink-0"></i>
                                        <span class="truncate">{{ Str::limit($k->ustadz, 18) }}</span>
                                    </span>
                                    @if ($k->start_at)
                                        <span class="hidden sm:inline text-xs text-muted-foreground">{{ $k->start_at->translatedFormat('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="sm:col-span-2 xl:col-span-3">
                            <x-empty-state icon="book-open" title="Kajian tidak ditemukan" message="Coba ubah kata kunci atau hapus filter.">
                                <button wire:click="resetFilters" class="mt-4"><x-ui.button size="sm" variant="outline">Reset filter</x-ui.button></button>
                            </x-empty-state>
                        </div>
                    @endforelse
                </div>

                @if ($kajians->hasPages())
                    <div class="mt-6">{{ $kajians->links() }}</div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="w-full min-w-0 space-y-4 sm:space-y-5">
                <div class="rounded-2xl border border-border bg-card p-4 sm:p-5 card-lift-sm w-full min-w-0">
                    <h2 class="font-bold text-foreground text-sm sm:text-base">Kajian Mendatang</h2>
                    <div class="mt-3.5 space-y-3 w-full min-w-0">
                        @forelse ($upcoming as $k)
                            <a href="{{ route('kajian.show', $k) }}" wire:navigate class="flex gap-3 items-center group w-full min-w-0">
                                <div class="grid size-10 sm:size-11 shrink-0 place-items-center rounded-xl bg-amber-500/10 text-center text-amber-600 dark:text-amber-400">
                                    <div>
                                        <p class="text-[0.58rem] font-bold uppercase leading-none">{{ $k->start_at?->translatedFormat('M') }}</p>
                                        <p class="text-xs sm:text-sm font-extrabold font-outfit">{{ $k->start_at?->format('d') }}</p>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 overflow-hidden">
                                    <p class="truncate text-xs sm:text-sm font-bold text-foreground group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $k->title }}</p>
                                    <p class="truncate text-[0.68rem] sm:text-xs text-muted-foreground">{{ $k->ustadz }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs sm:text-sm text-muted-foreground">Belum ada jadwal.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-500/25 bg-amber-500/5 p-4 sm:p-5 card-lift-sm w-full min-w-0">
                    <i data-lucide="bell-ring" class="size-4.5 sm:size-5 text-amber-500"></i>
                    <p class="mt-2 font-bold text-foreground text-xs sm:text-sm">Jangan sampai terlewat</p>
                    <p class="mt-1 text-[0.7rem] sm:text-xs text-muted-foreground leading-relaxed">Pasang website ini sebagai aplikasi untuk menerima pengingat kajian.</p>
                    <a href="{{ route('kalender') }}" wire:navigate class="mt-3 inline-block">
                        <x-ui.button size="sm" variant="outline" icon="calendar-days">Lihat Kalender</x-ui.button>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>
