<div>
    <x-page-hero title="Live Streaming" icon="radio"
                 subtitle="Ikuti kajian dan kegiatan masjid secara langsung dari mana saja." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if ($current)
            <div class="grid gap-6 lg:grid-cols-[1.6fr_1fr]">
                <div>
                    <div class="overflow-hidden rounded-2xl border border-border bg-black">
                        @if ($current->embed_url)
                            <iframe class="aspect-video w-full" src="{{ $current->embed_url }}" title="{{ $current->title }}"
                                    allowfullscreen loading="lazy" style="border:0"></iframe>
                        @else
                            <div class="grid aspect-video place-items-center bg-muted text-center">
                                <div>
                                    <i data-lucide="{{ \App\Models\Livestream::PLATFORMS[$current->platform]['icon'] ?? 'radio' }}" class="mx-auto size-10 text-muted-foreground"></i>
                                    <p class="mt-3 text-sm text-muted-foreground">Siaran berlangsung di {{ \App\Models\Livestream::PLATFORMS[$current->platform]['label'] ?? $current->platform }}</p>
                                    <a href="{{ $current->url }}" target="_blank" class="mt-4 inline-block">
                                        <x-ui.button icon="external-link">Tonton di {{ ucfirst($current->platform) }}</x-ui.button>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-5">
                        @if ($current->status === 'live')
                            <span class="inline-flex items-center gap-2 rounded-full bg-red-500/15 px-3 py-1 text-xs font-bold text-red-600 dark:text-red-400">
                                <span class="relative flex size-2">
                                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-red-500"></span>
                                    <span class="relative inline-flex size-2 rounded-full bg-red-500"></span>
                                </span>SEDANG LIVE
                            </span>
                        @else
                            <x-ui.badge variant="warning">Terjadwal {{ $current->start_at?->translatedFormat('l, d M Y H:i') }}</x-ui.badge>
                        @endif

                        <h1 class="mt-3 text-xl font-bold sm:text-2xl">{{ $current->title }}</h1>
                        @if ($current->description)
                            <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $current->description }}</p>
                        @endif
                    </div>
                </div>

                {{-- Chat pengganti: tautan platform + jadwal --}}
                <aside class="space-y-5">
                    <div class="rounded-2xl border border-border bg-card p-5">
                        <h2 class="font-semibold">Ikuti di Platform Lain</h2>
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            @foreach (\App\Models\Livestream::PLATFORMS as $key => $p)
                                @php $link = setting($key); @endphp
                                <a href="{{ $link ?: $current->url }}" target="_blank" rel="noopener"
                                   class="flex items-center gap-2 rounded-xl border border-border p-3 text-sm transition-colors hover:bg-accent">
                                    <i data-lucide="{{ $p['icon'] }}" class="size-4 text-primary"></i>{{ $p['label'] }}
                                </a>
                            @endforeach
                        </div>
                        <p class="mt-3 text-xs text-muted-foreground">
                            Ruang obrolan langsung tersedia di kolom komentar platform masing-masing.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-border bg-card p-5">
                        <h2 class="font-semibold">Jadwal Siaran</h2>
                        <div class="mt-4 space-y-3">
                            @forelse ($scheduled as $s)
                                <div class="flex gap-3">
                                    <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary">
                                        <i data-lucide="calendar-clock" class="size-4"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium">{{ $s->title }}</p>
                                        <p class="text-xs text-muted-foreground">{{ $s->start_at?->translatedFormat('D, d M · H:i') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-muted-foreground">Belum ada jadwal siaran.</p>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        @else
            <x-empty-state icon="radio" title="Belum ada siaran" message="Saat ini tidak ada siaran langsung maupun jadwal mendatang." />
        @endif

        {{-- Arsip --}}
        @if ($archive->isNotEmpty())
            <h2 class="mt-12 text-lg font-bold">Arsip Siaran</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($archive as $a)
                    <a href="{{ $a->url }}" target="_blank" rel="noopener"
                       class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-1 hover:shadow-lg">
                        <div class="relative aspect-video overflow-hidden bg-muted">
                            <img src="{{ img_url($a->thumbnail, 'live'.$a->id) }}" alt="{{ $a->title }}" class="size-full object-cover" />
                            <span class="absolute inset-0 grid place-items-center bg-black/30 text-white opacity-0 transition-opacity group-hover:opacity-100">
                                <i data-lucide="play" class="size-8"></i>
                            </span>
                        </div>
                        <div class="p-4">
                            <p class="line-clamp-2 font-medium leading-snug">{{ $a->title }}</p>
                            <p class="mt-1 text-xs text-muted-foreground">{{ $a->start_at?->translatedFormat('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
