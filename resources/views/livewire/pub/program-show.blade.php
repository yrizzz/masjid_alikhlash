<div>
    <div class="relative h-48 sm:h-72 w-full overflow-hidden bg-stone-950">
        <img src="{{ img_url($program->cover, $program->slug) }}" alt="{{ $program->title }}" class="size-full object-cover opacity-60" />
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent"></div>
        <div class="absolute inset-x-0 top-0 z-10">
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 sm:pt-6 lg:px-8">
                <a href="{{ route('program') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full bg-stone-950/70 border border-white/20 px-3.5 py-1.5 text-xs font-bold text-white backdrop-blur hover:bg-stone-900 transition-colors">
                    <i data-lucide="arrow-left" class="size-4 text-amber-400"></i>
                    <span>Kembali ke Daftar Program</span>
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto -mt-20 sm:-mt-24 max-w-7xl px-4 pb-16 sm:px-6 lg:px-8 relative z-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_18rem]">
            <article class="min-w-0">
                <div class="rounded-3xl border border-border bg-card p-6 sm:p-8 shadow-lg space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="grid size-11 place-items-center rounded-2xl bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/30 shadow-xs">
                            <i data-lucide="{{ $program->icon }}" class="size-5"></i>
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-extrabold uppercase tracking-wider {{ $program->status === 'active' ? 'bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/30' : 'bg-muted text-muted-foreground border border-border' }}">
                            {{ $program->status === 'active' ? 'Sedang Berjalan' : 'Selesai' }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-foreground font-jakarta leading-tight">{{ $program->title }}</h1>
                    <p class="text-sm sm:text-base text-muted-foreground font-medium leading-relaxed">{{ $program->excerpt }}</p>

                    <div class="prose-masjid border-t border-border pt-6 text-sm sm:text-base leading-relaxed text-foreground/90 font-normal">
                        {!! $program->description ?: '<p class="text-muted-foreground">Deskripsi lengkap program belum diisi.</p>' !!}
                    </div>
                </div>
            </article>

            <aside class="space-y-6">
                <div class="rounded-3xl border border-border bg-card p-6 shadow-sm space-y-4">
                    <h2 class="font-bold text-base border-b border-border pb-3 flex items-center gap-2">
                        <i data-lucide="info" class="size-4 text-amber-500"></i> Detail Program
                    </h2>
                    <dl class="space-y-3 text-xs sm:text-sm">
                        @if ($program->pic)
                            <div class="rounded-xl bg-muted/50 p-3 border border-border/40">
                                <dt class="text-[0.68rem] text-muted-foreground font-bold uppercase tracking-wider">Penanggung Jawab</dt>
                                <dd class="font-bold text-foreground mt-0.5">{{ $program->pic }}</dd>
                            </div>
                        @endif
                        @if ($program->start_date)
                            <div class="rounded-xl bg-muted/50 p-3 border border-border/40">
                                <dt class="text-[0.68rem] text-muted-foreground font-bold uppercase tracking-wider">Tanggal Mulai</dt>
                                <dd class="font-bold text-foreground mt-0.5">{{ tanggal_id($program->start_date, false) }}</dd>
                            </div>
                        @endif
                        @if ($program->end_date)
                            <div class="rounded-xl bg-muted/50 p-3 border border-border/40">
                                <dt class="text-[0.68rem] text-muted-foreground font-bold uppercase tracking-wider">Tanggal Selesai</dt>
                                <dd class="font-bold text-foreground mt-0.5">{{ tanggal_id($program->end_date, false) }}</dd>
                            </div>
                        @endif
                    </dl>

                    <div class="space-y-2.5 pt-2">
                        <a href="{{ route('donasi') }}" wire:navigate class="block">
                            <x-ui.button class="w-full justify-center bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-bold rounded-xl text-xs py-2.5 shadow" icon="hand-heart">
                                Dukung Program Ini
                            </x-ui.button>
                        </a>
                        <a href="{{ route('volunteer') }}" wire:navigate class="block">
                            <x-ui.button class="w-full justify-center rounded-xl text-xs py-2.5 font-bold" variant="outline" icon="hand-helping">
                                Jadi Relawan
                            </x-ui.button>
                        </a>
                    </div>
                </div>

                @if ($others->isNotEmpty())
                    <div class="rounded-3xl border border-border bg-card p-6 shadow-sm">
                        <h2 class="font-bold text-sm border-b border-border pb-3 flex items-center gap-2">
                            <i data-lucide="sparkles" class="size-4 text-amber-500"></i> Program Layanan Lainnya
                        </h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($others as $o)
                                <a href="{{ route('program.show', $o) }}" wire:navigate class="flex items-center gap-3 group p-2 rounded-xl hover:bg-muted/50 transition-colors">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        <i data-lucide="{{ $o->icon }}" class="size-4"></i>
                                    </span>
                                    <span class="min-w-0 truncate text-xs font-bold text-foreground group-hover:text-amber-600 transition-colors">{{ $o->title }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>
