<div>
    <x-page-hero title="Profil Masjid Al-Ikhlash"
                 subtitle="{{ setting('address', config('masjid.address')) }}"
                 icon="building-2" />

    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        {{-- Ringkasan angka --}}
        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            @foreach ([
                ['Berdiri Sejak', $founded ?: '—', 'calendar'],
                ['Luas Tanah', $landArea ? $landArea.' m²' : '—', 'ruler'],
                ['Kapasitas Jamaah', $capacity ? $capacity.' orang' : '—', 'users'],
                ['Legalitas', $legality ?: '—', 'badge-check'],
            ] as [$label, $value, $icon])
                <div class="rounded-2xl border border-border bg-card p-5">
                    <i data-lucide="{{ $icon }}" class="size-5 text-primary"></i>
                    <p class="mt-3 text-xs text-muted-foreground">{{ $label }}</p>
                    <p class="mt-0.5 truncate font-bold">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-10 grid gap-10 lg:grid-cols-[1.4fr_1fr]">
            <div class="space-y-10">
                {{-- Sejarah --}}
                <section>
                    <h2 class="text-xl font-bold tracking-tight">Sejarah Masjid</h2>
                    <div class="prose-masjid mt-4 text-sm sm:text-base">
                        {!! $history ?: '<p class="text-muted-foreground">Sejarah masjid belum diisi oleh pengurus.</p>' !!}
                    </div>
                </section>

                {{-- Timeline --}}
                @if ($milestones->isNotEmpty())
                    <section>
                        <h2 class="text-xl font-bold tracking-tight">Timeline Perkembangan</h2>
                        <ol class="mt-5 space-y-0">
                            @foreach ($milestones as $m)
                                <li class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <span class="grid size-10 shrink-0 place-items-center rounded-full bg-primary/10 text-primary">
                                            <i data-lucide="{{ $m->icon }}" class="size-4"></i>
                                        </span>
                                        @if (! $loop->last)<span class="my-1 w-px flex-1 bg-border"></span>@endif
                                    </div>
                                    <div class="pb-7">
                                        <span class="text-xs font-bold text-primary">{{ $m->year }}</span>
                                        <h3 class="mt-0.5 font-semibold">{{ $m->title }}</h3>
                                        <p class="mt-1 text-sm leading-relaxed text-muted-foreground">{{ $m->description }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </section>
                @endif

                {{-- Struktur pengurus (Bagan Organisasi) --}}
                <section class="overflow-hidden rounded-3xl border border-border bg-card/50 p-5 sm:p-8 backdrop-blur-sm shadow-sm">
                    <div class="flex items-center justify-between border-b border-border/80 pb-4 mb-6">
                        <div>
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                <i data-lucide="network" class="size-3.5"></i> Hirarki Organisasi
                            </span>
                            <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight text-foreground font-jakarta mt-0.5">Bagan Struktur Pengurus</h2>
                        </div>
                        <span class="rounded-full bg-amber-500/10 px-3 py-1 text-xs font-bold text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            Takmir Masjid
                        </span>
                    </div>

                    @if ($leaders->isNotEmpty())
                        {{-- Bagan Tree Hierarchy --}}
                        <div class="relative py-2 flex flex-col items-center">
                            {{-- Top Pimpinan (Puncak Bagan) --}}
                            @php $topLeader = $leaders->first(); $subLeaders = $leaders->slice(1); @endphp
                            
                            <div class="relative z-10 flex flex-col items-center w-full">
                                <div class="group relative rounded-2xl border-2 border-amber-500/40 bg-card p-5 text-center shadow-lg transition-all hover:scale-105 hover:border-amber-500 min-w-[220px] max-w-xs mx-auto">
                                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gradient-to-r from-amber-600 to-amber-800 px-3 py-0.5 text-[0.65rem] font-extrabold text-white shadow-sm uppercase tracking-wider">
                                        Pimpinan Utama
                                    </div>
                                    <x-ui.avatar :src="$topLeader->photo ? img_url($topLeader->photo) : null" :name="$topLeader->name" size="xl" class="mx-auto border-2 border-amber-500/30 shadow-md mt-1" />
                                    <p class="mt-3 font-bold text-foreground text-sm sm:text-base">{{ $topLeader->name }}</p>
                                    <p class="text-xs font-semibold text-amber-600 dark:text-amber-400">{{ $topLeader->position }}</p>
                                    <p class="mt-1 text-[0.7rem] text-muted-foreground">Periode {{ $topLeader->period }}</p>
                                    @if ($topLeader->phone)
                                        <a href="tel:{{ $topLeader->phone }}" class="mt-2 inline-flex items-center gap-1 text-[0.7rem] font-medium text-amber-600 hover:underline">
                                            <i data-lucide="phone" class="size-3"></i>{{ $topLeader->phone }}
                                        </a>
                                    @endif
                                </div>

                                {{-- Vertical Line Down from Top Leader --}}
                                @if ($subLeaders->isNotEmpty() || count($staff) > 0)
                                    <div class="w-0.5 h-8 bg-amber-500/40"></div>
                                @endif
                            </div>

                            {{-- Sub-Leaders Row (Sekretaris, Bendahara, Penasihat, dll) --}}
                            @if ($subLeaders->isNotEmpty())
                                <div class="relative w-full flex flex-col items-center">
                                    {{-- Horizontal Connector Line --}}
                                    @if ($subLeaders->count() > 1)
                                        <div class="hidden sm:block relative w-3/4 max-w-xl h-0.5 bg-amber-500/30">
                                            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-0.5 h-full bg-amber-500/40"></div>
                                        </div>
                                    @endif

                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mt-2 sm:mt-4 w-full max-w-2xl">
                                        @foreach ($subLeaders as $p)
                                            <div class="relative flex flex-col items-center">
                                                <div class="hidden sm:block w-0.5 h-4 bg-amber-500/30 -mt-4 mb-2"></div>
                                                <div class="w-full rounded-2xl border border-amber-500/25 bg-card/90 p-4 text-center shadow-sm hover:border-amber-500/50 transition-all">
                                                    <x-ui.avatar :src="$p->photo ? img_url($p->photo) : null" :name="$p->name" size="lg" class="mx-auto border border-amber-500/20" />
                                                    <p class="mt-2.5 font-bold text-xs sm:text-sm text-foreground">{{ $p->name }}</p>
                                                    <p class="text-[0.75rem] font-semibold text-amber-600 dark:text-amber-400">{{ $p->position }}</p>
                                                    <p class="mt-0.5 text-[0.65rem] text-muted-foreground">Periode {{ $p->period }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if (count($staff) > 0)
                                        <div class="w-0.5 h-8 bg-amber-500/30 mt-4"></div>
                                    @endif
                                </div>
                            @endif

                            {{-- Staff / Divisi Rows --}}
                            @if (count($staff) > 0)
                                <div class="w-full space-y-6 mt-2">
                                    <div class="relative flex items-center justify-center">
                                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-dashed border-amber-500/30"></div></div>
                                        <span class="relative bg-card px-4 text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 border border-amber-500/20 rounded-full py-1">
                                            Bidang & Divisi Pelaksana
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                        @foreach ($staff as $division => $members)
                                            <div class="rounded-2xl border border-border bg-card/80 p-4 sm:p-5 space-y-3">
                                                <div class="flex items-center gap-2 border-b border-border/60 pb-2.5">
                                                    <span class="grid size-7 place-items-center rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                        <i data-lucide="users" class="size-3.5"></i>
                                                    </span>
                                                    <h3 class="text-xs sm:text-sm font-extrabold uppercase tracking-wide text-foreground">
                                                        {{ $division ?: 'Pengurus Harian' }}
                                                    </h3>
                                                </div>
                                                <div class="grid gap-2.5">
                                                    @foreach ($members as $p)
                                                        <div class="flex items-center gap-3 rounded-xl border border-border/60 bg-muted/40 p-2.5 hover:bg-muted/80 transition-colors">
                                                            <x-ui.avatar :src="$p->photo ? img_url($p->photo) : null" :name="$p->name" size="sm" class="shrink-0" />
                                                            <div class="min-w-0 flex-1">
                                                                <p class="truncate text-xs font-bold text-foreground">{{ $p->name }}</p>
                                                                <p class="truncate text-[0.7rem] text-amber-600 dark:text-amber-400 font-medium">{{ $p->position }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </section>
            </div>

            <div class="space-y-6">
                {{-- Visi misi --}}
                <div class="rounded-2xl border border-border bg-card p-6">
                    <h2 class="flex items-center gap-2 font-bold"><i data-lucide="target" class="size-4 text-primary"></i>Visi</h2>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $vision ?: 'Belum diisi.' }}</p>

                    <h2 class="mt-6 flex items-center gap-2 font-bold"><i data-lucide="list-checks" class="size-4 text-primary"></i>Misi</h2>
                    <ul class="mt-2 space-y-2">
                        @forelse ($mission as $item)
                            <li class="flex gap-2.5 text-sm leading-relaxed text-muted-foreground">
                                <i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-primary"></i>{{ $item }}
                            </li>
                        @empty
                            <li class="text-sm text-muted-foreground">Belum diisi.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Fasilitas --}}
                @if ($facilities->isNotEmpty())
                    <div class="rounded-2xl border border-border bg-card p-6">
                        <h2 class="font-bold">Fasilitas</h2>
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            @foreach ($facilities as $f)
                                <div class="flex items-center gap-2.5">
                                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
                                        <i data-lucide="{{ $f->icon }}" class="size-4"></i>
                                    </span>
                                    <span class="truncate text-sm">{{ $f->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Virtual tour --}}
                <div class="overflow-hidden rounded-2xl border border-border bg-card">
                    @if ($tour)
                        <iframe src="{{ $tour }}" class="aspect-video w-full" style="border:0" loading="lazy" allowfullscreen title="Virtual Tour 360°"></iframe>
                    @else
                        <div class="relative aspect-video">
                            <img src="{{ route('placeholder', ['seed' => 'tour360']) }}" alt="" class="size-full object-cover" />
                            <div class="absolute inset-0 grid place-items-center bg-black/45 text-center text-white">
                                <div>
                                    <i data-lucide="view" class="mx-auto size-8"></i>
                                    <p class="mt-2 text-sm font-semibold">Virtual Tour 360°</p>
                                    <p class="text-xs text-white/70">Segera hadir</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="p-4">
                        <p class="text-sm font-semibold">Jelajahi masjid secara virtual</p>
                        <p class="mt-1 text-xs text-muted-foreground">Ruang utama, aula, dan area TPQ dalam tampilan 360 derajat.</p>
                    </div>
                </div>

                <a href="{{ route('galeri') }}" wire:navigate class="block">
                    <x-ui.button variant="outline" class="w-full" icon="images">Lihat Galeri Kegiatan</x-ui.button>
                </a>
            </div>
        </div>
    </div>
</div>
