<div>
    <x-page-hero title="Donasi & Campaign" icon="hand-heart"
                 subtitle="Galang dana bergaya crowdfunding — transparan, tercatat, dan dapat dipantau progresnya." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-10 sm:px-6 lg:px-8">
        {{-- Statistik --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 w-full min-w-0">
            @foreach ([
                ['Total Terkumpul', rupiah_short($totalRaised), 'wallet'],
                ['Donatur', number_format($donorCount, 0, ',', '.').' transaksi', 'users'],
                ['Campaign Aktif', $campaigns->where('status', 'active')->count().' program', 'target'],
            ] as [$label, $value, $icon])
                <div class="rounded-2xl border border-border bg-card p-5 card-lift-sm">
                    <i data-lucide="{{ $icon }}" class="size-5 text-amber-500"></i>
                    <p class="mt-3 text-xs text-muted-foreground">{{ $label }}</p>
                    <p class="text-xl font-bold font-outfit mt-0.5">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_19rem] w-full min-w-0">
            <div class="w-full min-w-0">
                <div class="flex gap-1 rounded-xl bg-muted p-1">
                    @foreach (['active' => 'Berjalan', 'finished' => 'Selesai', 'all' => 'Semua'] as $key => $label)
                        <button wire:click="$set('filter', '{{ $key }}')"
                                class="flex-1 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $filter === $key ? 'bg-background shadow-sm text-amber-600 dark:text-amber-400 font-bold' : 'text-muted-foreground hover:text-foreground' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 w-full min-w-0">
                    @forelse ($campaigns as $c)
                        <a href="{{ route('donasi.show', $c) }}" wire:navigate
                           class="group flex flex-col overflow-hidden rounded-2xl border border-border bg-card card-transition">
                            <div class="relative aspect-[16/10] overflow-hidden bg-muted">
                                <img src="{{ img_url($c->cover, $c->slug) }}" alt="{{ $c->title }}"
                                     class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                @if ($c->is_featured)
                                    <span class="absolute start-3 top-3 rounded-full bg-amber-500 px-2.5 py-1 text-xs font-bold text-stone-950">PRIORITAS</span>
                                @endif
                                @if ($c->days_left !== null)
                                    <span class="absolute end-3 top-3 rounded-full bg-background/90 px-2.5 py-1 text-xs font-semibold backdrop-blur">
                                        {{ $c->days_left > 0 ? $c->days_left.' hari lagi' : 'Berakhir' }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex flex-1 flex-col p-5">
                                <h3 class="line-clamp-2 font-bold leading-snug text-foreground group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $c->title }}</h3>
                                <p class="mt-1.5 line-clamp-2 flex-1 text-sm text-muted-foreground">{{ $c->excerpt }}</p>

                                <div class="mt-4">
                                    <div class="fund-bar"><span style="width: {{ $c->progress }}%"></span></div>
                                    <div class="mt-2 flex items-end justify-between">
                                        <div>
                                            <p class="text-lg font-extrabold font-outfit text-amber-600 dark:text-amber-400">{{ rupiah_short($c->collected) }}</p>
                                            <p class="text-xs text-muted-foreground">terkumpul dari {{ rupiah_short($c->target) }}</p>
                                        </div>
                                        <div class="text-end">
                                            <p class="text-sm font-extrabold font-outfit text-amber-500">{{ $c->progress }}%</p>
                                            <p class="text-xs text-muted-foreground">{{ $c->donor_count }} donatur</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="sm:col-span-2">
                            <x-empty-state icon="hand-heart" title="Belum ada campaign" message="Pengurus belum membuka penggalangan dana." />
                        </div>
                    @endforelse
                </div>
            </div>

            <aside class="space-y-5">
                <div class="rounded-2xl border border-border bg-card p-5 card-lift-sm">
                    <h2 class="font-bold text-foreground">Donatur Terbaru</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($recent as $d)
                            <div class="flex items-center gap-3">
                                <x-ui.avatar :name="$d->display_name" size="sm" />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium">{{ $d->display_name }}</p>
                                    <p class="truncate text-xs text-muted-foreground">{{ $d->campaign?->title ?? 'Donasi umum' }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-extrabold font-outfit text-amber-600 dark:text-amber-400">{{ rupiah_short($d->amount) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">Jadilah donatur pertama.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-2xl border border-amber-500/25 bg-amber-500/5 p-5 card-lift-sm">
                    <i data-lucide="shield-check" class="size-5 text-amber-500"></i>
                    <p class="mt-2.5 font-bold text-foreground">Amanah & Transparan</p>
                    <p class="mt-1 text-sm text-muted-foreground">Setiap donasi tercatat dan laporannya bisa dilihat jamaah kapan saja.</p>
                    <a href="{{ route('transparansi') }}" wire:navigate class="mt-3 inline-block">
                        <x-ui.button size="sm" variant="outline" icon="chart-pie">Lihat Laporan</x-ui.button>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</div>
