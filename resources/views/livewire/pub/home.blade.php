@php
    $heroImage = asset('images/hero-bg.png');
@endphp

<div class="font-jakarta bg-background text-foreground antialiased selection:bg-amber-500 selection:text-stone-950">
    {{-- ══════════════ HERO SECTION — EXACT MATCH DESIGN ══════════════ --}}
    <section class="hero-shell relative min-h-[92vh] sm:min-h-screen overflow-hidden flex flex-col justify-between">
        {{-- Full bleed background photo --}}
        <div class="hero-bg absolute inset-0 z-0 bg-cover bg-center bg-no-repeat transition-transform duration-1000 scale-105"
             style="background-image: url('{{ $heroImage }}')"></div>

        {{-- Gradient Overlays: Dark gradient on left to make text pop, subtle glow on architecture --}}
        <div class="absolute inset-0 z-1 pointer-events-none"
             style="background:
                linear-gradient(90deg, rgba(12,8,4,0.92) 0%, rgba(16,10,5,0.78) 45%, rgba(18,12,6,0.30) 75%, rgba(10,6,2,0.65) 100%),
                linear-gradient(180deg, rgba(10,6,2,0.70) 0%, transparent 35%, rgba(12,8,4,0.95) 100%),
                radial-gradient(ellipse 50% 50% at 20% 40%, rgba(217,119,6,0.18) 0%, transparent 70%);"></div>

        {{-- MAIN CONTENT CONTAINER: 2-Column layout matching reference screenshot --}}
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 pt-24 sm:pt-28 pb-36">
            <div class="grid lg:grid-cols-12 gap-8 items-center">

                {{-- Left Content Column --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Arabic Verse Ornament --}}
                    <div class="space-y-1">
                        <div class="flex items-center gap-3">
                            <span class="h-px w-8 bg-amber-400/40"></span>
                            <p class="font-amiri text-2xl sm:text-3xl text-amber-200/90 tracking-wide drop-shadow-md">
                                إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ مَنْ آمَنَ بِاللَّهِ
                            </p>
                            <span class="h-px w-8 bg-amber-400/40"></span>
                        </div>
                        <p class="text-[0.7rem] font-extrabold uppercase tracking-[0.25em] text-amber-400/80">
                            QS. AT-TAUBAH : 18
                        </p>
                    </div>

                    {{-- Hero Mosque Title & Subtitle --}}
                    <div>
                        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-tight font-jakarta drop-shadow-lg">
                            Masjid Jami’<br />
                            <span class="font-serif italic font-normal text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-amber-400 to-amber-500 drop-shadow-sm">
                                Al-Ikhlash
                            </span>
                        </h1>
                        <p class="mt-3 text-sm sm:text-base text-stone-200/90 font-medium leading-relaxed max-w-lg">
                            Pusat Ibadah, Ilmu, dan Pemberdayaan Umat<br />
                            <span class="text-amber-400 font-bold">Kelurahan Kerten, Laweyan, Surakarta.</span>
                        </p>
                    </div>

                    {{-- 4 Stat Cards Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3 pt-2">
                        <div class="rounded-2xl border border-white/15 bg-stone-950/60 p-3.5 backdrop-blur-md hover:border-amber-500/40 transition-colors shadow-lg">
                            <div class="flex items-center gap-2 text-amber-400 mb-1">
                                <i data-lucide="users" class="size-4 shrink-0"></i>
                                <span class="font-outfit text-base font-black text-white">950+</span>
                            </div>
                            <p class="text-[0.68rem] text-stone-300 font-medium leading-none">Jamaah Aktif</p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-stone-950/60 p-3.5 backdrop-blur-md hover:border-amber-500/40 transition-colors shadow-lg">
                            <div class="flex items-center gap-2 text-amber-400 mb-1">
                                <i data-lucide="book-open" class="size-4 shrink-0"></i>
                                <span class="font-outfit text-base font-black text-white">12</span>
                            </div>
                            <p class="text-[0.68rem] text-stone-300 font-medium leading-none">Kajian Bulan Ini</p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-stone-950/60 p-3.5 backdrop-blur-md hover:border-amber-500/40 transition-colors shadow-lg">
                            <div class="flex items-center gap-2 text-amber-400 mb-1">
                                <i data-lucide="heart" class="size-4 shrink-0"></i>
                                <span class="font-outfit text-base font-black text-white">Rp 18jt+</span>
                            </div>
                            <p class="text-[0.68rem] text-stone-300 font-medium leading-none">Donasi Bulan Ini</p>
                        </div>

                        <div class="rounded-2xl border border-white/15 bg-stone-950/60 p-3.5 backdrop-blur-md hover:border-amber-500/40 transition-colors shadow-lg">
                            <div class="flex items-center gap-2 text-amber-400 mb-1">
                                <i data-lucide="user-check" class="size-4 shrink-0"></i>
                                <span class="font-outfit text-base font-black text-white">25</span>
                            </div>
                            <p class="text-[0.68rem] text-stone-300 font-medium leading-none">Relawan Aktif</p>
                        </div>
                    </div>

                    {{-- Action Pill Buttons Row --}}
                    <div class="flex flex-wrap items-center gap-2 pt-2">
                        <a href="{{ route('donasi') }}" wire:navigate
                           class="inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 px-5.5 py-2.5 text-xs sm:text-sm font-extrabold text-stone-950 shadow-xl shadow-amber-500/30 hover:brightness-110 active:scale-95 transition-all">
                            <i data-lucide="hand-heart" class="size-4"></i>
                            <span>Donasi Sekarang</span>
                            <i data-lucide="arrow-right" class="size-3.5 ms-0.5"></i>
                        </a>

                        <a href="{{ route('kajian') }}" wire:navigate
                           class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-stone-950/60 px-4 py-2.5 text-xs sm:text-sm font-bold text-white backdrop-blur hover:bg-stone-900/80 hover:border-amber-400/50 transition-all">
                            <i data-lucide="book-open" class="size-4 text-amber-400"></i>
                            <span>Jadwal Kajian</span>
                        </a>

                        <a href="{{ route('live') }}" wire:navigate
                           class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-stone-950/60 px-4 py-2.5 text-xs sm:text-sm font-bold text-white backdrop-blur hover:bg-stone-900/80 hover:border-amber-400/50 transition-all">
                            <i data-lucide="radio" class="size-4 text-amber-400"></i>
                            <span>Live Streaming</span>
                        </a>

                        <a href="{{ route('imam') }}" wire:navigate
                           class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-stone-950/60 px-4 py-2.5 text-xs sm:text-sm font-bold text-white backdrop-blur hover:bg-stone-900/80 hover:border-amber-400/50 transition-all">
                            <i data-lucide="user-check" class="size-4 text-amber-400"></i>
                            <span>Jadwal Imam</span>
                        </a>

                        <a href="{{ route('kiblat') }}" wire:navigate
                           class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-stone-950/60 px-4 py-2.5 text-xs sm:text-sm font-bold text-white backdrop-blur hover:bg-stone-900/80 hover:border-amber-400/50 transition-all">
                            <i data-lucide="compass" class="size-4 text-amber-400"></i>
                            <span>Arah Kiblat</span>
                        </a>
                    </div>
                </div>

                {{-- Right Column: Mosque Arch Graphic + Floating Live Kajian Box --}}
                <div class="lg:col-span-5 relative mt-6 lg:mt-0 flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md group">
                        {{-- Mosque Arch Container --}}
                        <div class="overflow-hidden rounded-[2.5rem] border-2 border-amber-500/30 bg-stone-950 shadow-2xl relative aspect-[4/4.8]">
                            <img src="{{ asset('images/mosque-arch.png') }}" alt="Masjid Al-Ikhlash" class="size-full object-cover transition-transform duration-1000 group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-transparent to-stone-950/40"></div>
                        </div>

                        {{-- Floating Live Kajian Card positioned on the lower right of the Arch --}}
                        <div class="absolute -bottom-6 -start-4 sm:-start-6 z-20 w-[90%] sm:w-80 rounded-3xl border border-amber-500/40 bg-stone-950/90 p-4.5 backdrop-blur-xl shadow-2xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 px-2.5 py-0.5 text-[0.65rem] font-extrabold uppercase tracking-wider text-emerald-400">
                                    <span class="size-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                                    LIVE
                                </span>
                                <span class="text-[0.68rem] text-stone-400 font-medium">Kajian Rutin Malam Ini</span>
                            </div>
                            <div>
                                <p class="font-extrabold text-sm text-white font-jakarta">Kitab Riyadhus Shalihin</p>
                                <p class="text-xs text-stone-300 font-medium mt-0.5 flex items-center gap-1">
                                    <i data-lucide="user" class="size-3 text-amber-400"></i> Ust. Abu Hurairah, Lc.
                                </p>
                            </div>
                            <div class="border-t border-white/10 pt-2 flex items-center justify-between text-[0.68rem] text-stone-400 font-medium">
                                <span class="flex items-center gap-1 text-emerald-400 font-bold">
                                    <span class="size-1.5 rounded-full bg-emerald-400"></span> 150 Jamaah Online
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ═══ PRAYER TIME FLOATING BAR — EXACT MATCH BOTTOM BAR ═══ --}}
        <div class="relative z-20 w-full border-t border-amber-500/20 bg-stone-950/90 backdrop-blur-2xl py-3 shadow-2xl">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">

                    {{-- Left Date Card --}}
                    <div class="flex items-center gap-3 rounded-2xl bg-white/5 border border-white/10 px-4 py-2.5 text-xs text-white shrink-0 w-full lg:w-auto">
                        <div class="grid size-9 place-items-center rounded-xl bg-amber-500/20 text-amber-400">
                            <i data-lucide="calendar" class="size-4"></i>
                        </div>
                        <div>
                            <p class="font-bold text-amber-300 font-jakarta">{{ $hijri['day_name'] }}, {{ $hijri['formatted'] }}</p>
                            <p class="text-[0.7rem] text-stone-300 font-medium">{{ tanggal_id(now()) }} M</p>
                        </div>
                    </div>

                    {{-- Prayer Times Columns --}}
                    <div class="flex flex-1 items-center justify-between gap-2 overflow-x-auto w-full py-1 scrollbar-none px-2">
                        @foreach ($prayer['times'] as $key => $time)
                            @php $isActive = $prayer['current'] === $key; @endphp
                            <div class="flex-1 min-w-[5.5rem] rounded-xl px-3 py-2 text-center transition-all {{ $isActive ? 'bg-amber-500/20 border border-amber-500/40 shadow-lg' : 'bg-transparent' }}">
                                <p class="text-[0.65rem] font-extrabold uppercase tracking-wider {{ $isActive ? 'text-amber-300' : 'text-stone-400' }}">
                                    {{ \App\Services\PrayerTimeService::PRAYERS[$key] }}
                                </p>
                                <p class="font-outfit text-base font-extrabold tabular-nums {{ $isActive ? 'text-white' : 'text-stone-200' }}">
                                    {{ $time->format('H:i') }}
                                </p>
                                @if ($isActive)
                                    <p class="text-[0.58rem] font-bold text-amber-400 uppercase tracking-tight mt-0.5">Sedang Berlangsung</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Right Countdown Box with Circular/Gauge Indicator --}}
                    <div x-data="countdown({{ $prayer['seconds_left'] }})" class="flex items-center gap-3 rounded-2xl bg-amber-500/15 border border-amber-500/30 px-4 py-2 shrink-0 w-full lg:w-auto justify-between lg:justify-start">
                        <div>
                            <p class="text-[0.65rem] font-bold uppercase tracking-wider text-amber-400/80">Menuju {{ $prayer['next_label'] }}</p>
                            <p class="font-outfit text-base font-extrabold tabular-nums text-white" x-text="formatted"></p>
                            <p class="text-[0.68rem] text-stone-300 font-medium" x-text="subtext"></p>
                        </div>
                        <div class="relative size-10 grid place-items-center rounded-full bg-stone-900 border border-amber-400/40 text-amber-400">
                            <i data-lucide="clock" class="size-5 animate-pulse"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- ══════════════ AKSES CEPAT LAYANAN ISLAMI ══════════════ --}}
    <section class="border-b border-emerald-500/10 bg-card/60 backdrop-blur-md py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4">
                @foreach ([
                    ['Al-Quran Digital', 'book-open-text', 'quran', 'Baca & Tafsir'],
                    ['Donasi & Infaq', 'hand-heart', 'donasi', 'Galang Dana'],
                    ['Kalkulator Zakat', 'calculator', 'zakat', 'Hitung Zakat'],
                    ['Kajian & Live', 'video', 'kajian', 'Majelis Ilmu'],
                    ['Booking Ruangan', 'building-2', 'booking', 'Fasilitas Masjid'],
                    ['UMKM Jamaah', 'store', 'umkm', 'Ekonomi Umat'],
                ] as [$label, $icon, $r, $sub])
                    <a href="{{ route($r) }}" wire:navigate
                       class="group flex flex-col items-center gap-2 rounded-2xl border border-amber-700/20 bg-background p-4 text-center card-transition hover:border-amber-500/50">
                        <span class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br from-amber-600/15 via-stone-500/10 to-amber-900/20 text-amber-700 dark:text-amber-400 group-hover:bg-gradient-to-br group-hover:from-amber-600 group-hover:to-amber-800 group-hover:text-white transition-all shadow-sm">
                            <i data-lucide="{{ $icon }}" class="size-6"></i>
                        </span>
                        <div>
                            <span class="block text-xs font-bold text-foreground group-hover:text-amber-700 dark:group-hover:text-amber-400 transition-colors">{{ $label }}</span>
                            <span class="block text-[0.65rem] text-muted-foreground font-medium">{{ $sub }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══════════════ KAJIAN & KHUTBAH JUMAT ══════════════ --}}
    <section class="mx-auto max-w-7xl w-full min-w-0 px-4 py-8 sm:py-16 sm:px-6 lg:px-8 bg-islamic-pattern">
        <div class="grid gap-8 lg:grid-cols-3 w-full min-w-0">
            {{-- Kiri: Kajian Mendatang --}}
            <div class="lg:col-span-2 w-full min-w-0 space-y-6">
                <div class="flex items-center justify-between gap-3 border-b border-border pb-4">
                    <div class="min-w-0 flex-1">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                            <i data-lucide="book-open" class="size-3.5"></i> Majelis Ilmu
                        </span>
                        <h2 class="text-base xs:text-xl sm:text-3xl font-extrabold tracking-tight text-foreground font-jakarta mt-0.5 truncate">Kajian Rutin & Tematik</h2>
                    </div>
                    <a href="{{ route('kajian') }}" wire:navigate class="shrink-0 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1">
                        <span>Lihat Semua</span>
                        <i data-lucide="arrow-right" class="size-3.5"></i>
                    </a>
                </div>

                {{-- Kajian Hari Ini Highlight --}}
                @if ($todayKajian->isNotEmpty())
                    <div class="w-full rounded-3xl border border-amber-500/30 bg-gradient-to-br from-amber-500/10 via-amber-600/5 to-transparent p-4 sm:p-6 relative overflow-hidden shadow-sm">
                        <div class="flex items-center justify-between gap-2">
                            <p class="flex items-center gap-2 text-xs sm:text-sm font-bold text-amber-600 dark:text-amber-400">
                                <i data-lucide="calendar-check" class="size-4"></i> Hari ini di {{ setting('name', config('masjid.name')) }}
                            </p>
                            <span class="rounded-full bg-amber-500/20 px-2.5 py-0.5 text-[0.65rem] sm:text-[0.68rem] font-bold text-amber-700 dark:text-amber-300 uppercase">Live Majelis</span>
                        </div>
                        <div class="mt-3.5 space-y-2.5">
                            @foreach ($todayKajian as $k)
                                <a href="{{ route('kajian.show', $k) }}" wire:navigate class="flex items-center gap-3 sm:gap-4 rounded-2xl bg-card p-3.5 sm:p-4 transition-all hover:shadow-md border border-border w-full">
                                    <span class="grid size-11 sm:size-12 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-amber-600 to-amber-800 font-outfit text-xs sm:text-sm font-extrabold text-white shadow-md">
                                        {{ $k->start_at?->format('H:i') }}
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate font-bold text-xs sm:text-base text-foreground">{{ $k->title }}</span>
                                        <span class="block truncate text-[0.7rem] sm:text-xs font-medium text-muted-foreground flex items-center gap-1.5 mt-0.5">
                                            <i data-lucide="user" class="size-3.5 text-amber-500"></i> {{ $k->ustadz }}
                                        </span>
                                    </span>
                                    <i data-lucide="chevron-right" class="size-4 sm:size-5 shrink-0 text-muted-foreground"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Grid Kajian --}}
                <div class="grid grid-cols-1 gap-4 sm:gap-5 sm:grid-cols-3 w-full min-w-0">
                    @forelse ($kajians as $k)
                        <a href="{{ route('kajian.show', $k) }}" wire:navigate
                           class="group overflow-hidden rounded-2xl border border-border bg-card card-transition shadow-sm w-full hover:border-amber-500/50">
                            <div class="aspect-[16/10] sm:aspect-[4/3] w-full overflow-hidden bg-muted relative">
                                <img src="{{ img_url($k->poster, $k->slug) }}" alt="{{ $k->title }}"
                                     class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                <span class="absolute top-3 left-3 rounded-full bg-black/75 backdrop-blur-md px-3 py-1 text-[0.68rem] font-bold text-amber-300 border border-white/20">
                                    {{ $k->start_at?->translatedFormat('l, d M') }}
                                </span>
                            </div>
                            <div class="p-4">
                                <p class="text-xs font-bold text-amber-600 dark:text-amber-400">{{ $k->start_at?->format('H:i') }} WIB</p>
                                <h3 class="mt-1 line-clamp-2 font-bold text-foreground leading-snug group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $k->title }}</h3>
                                <p class="mt-2 flex items-center gap-1.5 text-xs text-muted-foreground font-medium">
                                    <i data-lucide="user-round" class="size-3.5 text-amber-500"></i>{{ $k->ustadz }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="sm:col-span-3 rounded-2xl border border-dashed border-border py-12 text-center text-sm text-muted-foreground w-full">
                            Belum ada jadwal kajian terbaru.
                        </p>
                    @endforelse
                </div>
            </div>

            {{-- Kanan: Khutbah Jumat & Agenda --}}
            <div class="w-full min-w-0 space-y-6">
                @if ($jumat)
                    <div class="relative overflow-hidden rounded-3xl border border-amber-500/40 bg-stone-950 text-white p-5 sm:p-7 shadow-2xl flex flex-col items-center justify-center text-center">
                        {{-- Dark background layers --}}
                        <div class="absolute inset-0 bg-stone-950 z-0"></div>
                        <div class="absolute inset-0 bg-islamic-pattern opacity-20 z-0"></div>

                        <div class="relative z-10 w-full flex flex-col items-center text-center">
                            {{-- Header badge --}}
                            <div class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 border border-amber-500/40 px-3.5 py-1 text-xs font-bold text-amber-300 mb-3">
                                <i data-lucide="mic-vocal" class="size-4 text-amber-400"></i>
                                <span>Khutbah Jumat Minggu Ini</span>
                            </div>

                            {{-- Theme title --}}
                            <h3 class="text-base sm:text-lg font-extrabold leading-snug text-white font-jakarta max-w-md mx-auto drop-shadow-sm">
                                {{ $jumat->theme }}
                            </h3>

                            {{-- Details list --}}
                            <div class="mt-4 w-full max-w-md space-y-2.5 text-xs font-medium rounded-2xl bg-stone-900/90 p-4 border border-amber-500/25 shadow-inner text-start">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-stone-300 font-medium">Khatib:</span>
                                    <span class="font-bold text-amber-300 text-end">{{ $jumat->khatib }}</span>
                                </div>
                                @if ($jumat->imam)
                                    <div class="flex items-center justify-between gap-3 border-t border-white/10 pt-2">
                                        <span class="text-stone-300 font-medium">Imam Sholat:</span>
                                        <span class="font-bold text-amber-300 text-end">{{ $jumat->imam }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between gap-3 border-t border-white/10 pt-2">
                                    <span class="text-stone-300 font-medium">Tanggal:</span>
                                    <span class="font-bold text-amber-400 text-end">{{ tanggal_id($jumat->date, false) }}</span>
                                </div>
                            </div>

                            {{-- Action link --}}
                            <a href="{{ route('jumat') }}" wire:navigate class="mt-4 inline-flex items-center justify-center gap-2 text-xs font-bold text-amber-400 hover:text-amber-300 transition-colors">
                                <span>Jadwal Khutbah Jumat Lengkap</span>
                                <i data-lucide="arrow-right" class="size-3.5"></i>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Agenda Terdekat --}}
                <div class="rounded-2xl sm:rounded-3xl border border-border bg-card p-4 sm:p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-border pb-3">
                        <h3 class="font-bold text-foreground font-jakarta flex items-center gap-2">
                            <i data-lucide="calendar-days" class="size-4 text-amber-600 dark:text-amber-400"></i>
                            <span>Agenda Terdekat</span>
                        </h3>
                        <a href="{{ route('kalender') }}" wire:navigate class="text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">Kalender</a>
                    </div>
                    <div class="mt-4 divide-y divide-border/60">
                        @forelse ($events as $e)
                            <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                                <div class="grid size-11 shrink-0 place-items-center rounded-xl text-center shadow-sm border border-border/40"
                                     style="background: {{ $e->type_color }}15; color: {{ $e->type_color }}">
                                    <div>
                                        <p class="text-[0.58rem] font-bold uppercase leading-none opacity-80">{{ $e->start_at->translatedFormat('M') }}</p>
                                        <p class="font-outfit text-base font-extrabold leading-tight mt-0.5">{{ $e->start_at->format('d') }}</p>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-xs sm:text-sm font-bold text-foreground leading-snug">{{ $e->title }}</p>
                                    <p class="truncate text-[0.7rem] sm:text-xs font-medium text-muted-foreground mt-0.5 flex items-center gap-1">
                                        <i data-lucide="clock" class="size-3 shrink-0 text-amber-500"></i>
                                        <span>{{ $e->all_day ? 'Sepanjang hari' : $e->start_at->format('H:i') }} WIB</span>
                                        @if ($e->location)
                                            <span class="truncate">· {{ $e->location }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-sm text-muted-foreground">Belum ada agenda terdekat.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ DONASI & GALANG DANA ══════════════ --}}
    @if ($campaigns->isNotEmpty())
        <section class="border-y border-amber-700/20 bg-gradient-to-b from-stone-950/20 via-background to-background py-8 sm:py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-3 border-b border-border pb-4">
                    <div class="min-w-0 flex-1">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-500">
                            <i data-lucide="hand-heart" class="size-3.5"></i> Donasi Transparan
                        </span>
                        <h2 class="text-base xs:text-xl sm:text-3xl font-extrabold tracking-tight text-foreground font-jakarta mt-0.5 truncate">Galang Dana & Infaq Umat</h2>
                    </div>
                    <a href="{{ route('donasi') }}" wire:navigate class="shrink-0 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline flex items-center gap-1">
                        <span>Semua Donasi</span>
                        <i data-lucide="arrow-right" class="size-3.5"></i>
                    </a>
                </div>

                <div class="mt-8 grid gap-6 md:grid-cols-3">
                    @foreach ($campaigns as $c)
                        <a href="{{ route('donasi.show', $c) }}" wire:navigate
                           class="group overflow-hidden rounded-3xl border border-border bg-card card-transition">
                            <div class="relative aspect-[16/10] overflow-hidden bg-muted">
                                <img src="{{ img_url($c->cover, $c->slug) }}" alt="{{ $c->title }}"
                                     class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                @if ($c->days_left !== null)
                                    <span class="absolute end-3 top-3 rounded-full bg-slate-950/80 px-3 py-1 text-xs font-bold text-amber-300 backdrop-blur-md border border-white/20">
                                        {{ $c->days_left > 0 ? $c->days_left.' hari lagi' : 'Berakhir' }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="line-clamp-2 font-bold text-foreground leading-snug group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $c->title }}</h3>

                                <div class="mt-5 space-y-2">
                                    <div class="fund-bar"><span style="width: {{ $c->progress }}%"></span></div>
                                    <div class="flex items-baseline justify-between pt-1">
                                        <div>
                                            <p class="font-outfit text-xl font-extrabold text-amber-600 dark:text-amber-400">{{ rupiah_short($c->collected) }}</p>
                                            <p class="text-xs font-medium text-muted-foreground">Terkumpul dari {{ rupiah_short($c->target) }}</p>
                                        </div>
                                        <span class="font-outfit text-sm font-extrabold text-amber-500 bg-amber-500/10 px-2.5 py-0.5 rounded-full">{{ $c->progress }}%</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ══════════════ TRANSPARANSI KEUANGAN REALTIME ══════════════ --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:py-16 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl border border-amber-700/25 bg-card shadow-xl bg-islamic-pattern card-transition">
            <div class="grid lg:grid-cols-[1fr_1.1fr]">
                <div class="p-8 sm:p-10 flex flex-col justify-between">
                    <div>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/15 px-3.5 py-1 text-xs font-bold text-amber-600 dark:text-amber-400 border border-amber-500/20">
                            <i data-lucide="shield-check" class="size-4"></i> Transparansi Realtime
                        </span>
                        <h2 class="mt-4 text-2xl font-extrabold tracking-tight text-foreground sm:text-3xl font-jakarta">
                            Laporan Keuangan Terbuka & Akuntabel
                        </h2>
                        <p class="mt-3 text-sm leading-relaxed text-muted-foreground">
                            Seluruh dana infaq, shadaqah, dan donasi masjid dicatat secara realtime dan dipublikasikan tanpa keraguan. Umat dapat memantau serta mengunduh laporan keuangan kapan saja.
                        </p>
                    </div>
                    <div class="mt-8">
                        <a href="{{ route('transparansi') }}" wire:navigate>
                            <x-ui.button icon="chart-pie" class="bg-gradient-to-r from-amber-600 to-amber-800 text-white font-bold px-6 py-3 rounded-2xl shadow-lg hover:scale-105 transition-all">Lihat Laporan Transparan</x-ui.button>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-px bg-border sm:grid-cols-2 lg:border-s lg:border-border">
                    @foreach ([
                        ['Pemasukan ' . now()->year, $income, 'trending-up', 'text-amber-600 dark:text-amber-400'],
                        ['Pengeluaran ' . now()->year, $expense, 'trending-down', 'text-rose-600 dark:text-rose-400'],
                        ['Saldo Kas Berjalan', $balance, 'wallet', 'text-amber-500'],
                        ['Donatur Terdaftar', $campaigns->sum('donor_count'), 'users', 'text-foreground'],
                    ] as $i => [$label, $value, $icon, $tone])
                        <div class="bg-card p-6 flex flex-col justify-center">
                            <span class="grid size-10 place-items-center rounded-xl bg-muted/60 mb-3">
                                <i data-lucide="{{ $icon }}" class="size-5 {{ $tone }}"></i>
                            </span>
                            <p class="text-xs font-medium text-muted-foreground">{{ $label }}</p>
                            <p class="mt-1 font-outfit text-xl font-extrabold tracking-tight {{ $tone }}">
                                {{ $i === 3 ? number_format($value, 0, ',', '.').' Donatur' : rupiah_short($value) }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ AL-QURAN DIGITAL & E-LIBRARY ══════════════ --}}
    <section class="border-t border-amber-700/30 bg-stone-950 bg-gradient-to-r from-stone-950 via-stone-900 to-amber-950 text-white py-8 sm:py-16 relative overflow-hidden bg-islamic-pattern">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                <div class="space-y-5">
                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 px-3.5 py-1 text-xs font-bold text-amber-300 border border-amber-500/30">
                        <i data-lucide="book-open-text" class="size-4 text-amber-400"></i> Al-Quran & Pustaka Digital
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight font-jakarta text-white">
                        Baca Al-Quran & Pelajari Hadits Langsung di Web
                    </h2>
                    <p class="text-amber-100/80 text-sm leading-relaxed font-medium">
                        Lengkap dengan teks Arab otentik, terjemahan bahasa Indonesia, audio murattal per ayat, serta tafsir ringkas tanpa perlu aplikasi tambahan.
                    </p>
                    <div class="grid grid-cols-2 gap-2.5 pt-2">
                        <a href="{{ route('quran') }}" wire:navigate
                           class="inline-flex items-center justify-center gap-1.5 rounded-xl sm:rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 px-2.5 sm:px-4 py-2.5 sm:py-3 text-[0.7rem] sm:text-xs font-extrabold text-stone-950 shadow-xl hover:scale-105 transition-all text-center">
                            <i data-lucide="book-marked" class="size-3.5 sm:size-4 shrink-0"></i> <span>Al-Quran Digital</span>
                        </a>
                        <a href="{{ route('pustaka') }}" wire:navigate
                           class="inline-flex items-center justify-center gap-1.5 rounded-xl sm:rounded-2xl border border-amber-500/30 bg-amber-500/10 px-2.5 sm:px-4 py-2.5 sm:py-3 text-[0.7rem] sm:text-xs font-bold text-amber-200 hover:bg-amber-500/20 transition-all text-center">
                            <i data-lucide="library" class="size-3.5 sm:size-4 shrink-0"></i> <span>Pustaka Digital</span>
                        </a>
                    </div>
                </div>

                {{-- Preview Verse Banner --}}
                <div class="rounded-3xl bg-stone-900/90 border border-amber-500/30 p-8 text-center space-y-4 shadow-2xl relative backdrop-blur">
                    <p class="bismillah-text text-amber-400">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</p>
                    <p class="quran-arabic text-amber-100 leading-widest">
                        اقْرَأْ بِاسْمِ رَبِّكَ الَّذِي خَلَقَ
                    </p>
                    <p class="text-xs text-amber-200/90 font-medium">
                        “Bacalah dengan (menyebut) nama Tuhanmu yang menciptakan.” <span class="text-amber-400 font-bold">(QS. Al-'Alaq: 1)</span>
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ LOKASI & PETA MASJID ══════════════ --}}
    <section class="border-t border-border py-8 sm:py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:px-6 lg:grid-cols-2 lg:px-8">
            <div class="space-y-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                        <i data-lucide="map-pin" class="size-3.5"></i> Lokasi & Arah Kiblat
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground font-jakarta mt-1">Berkunjung ke {{ setting('name', config('masjid.name')) }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ setting('address', config('masjid.address')) }}</p>
                </div>

                <dl class="grid grid-cols-2 gap-4">
                    @foreach ([
                        ['Arah Presisi Kiblat', number_format(app(\App\Services\PrayerTimeService::class)->qiblaDirection(), 1).'° dari Utara', 'compass'],
                        ['Jarak ke Ka\'bah', number_format(app(\App\Services\PrayerTimeService::class)->qiblaDistance(), 0, ',', '.').' km', 'route'],
                    ] as [$label, $value, $icon])
                        <div class="rounded-2xl border border-border bg-card p-5 card-lift-sm">
                            <i data-lucide="{{ $icon }}" class="size-6 text-amber-500"></i>
                            <dt class="mt-3 text-xs font-medium text-muted-foreground">{{ $label }}</dt>
                            <dd class="font-outfit text-lg font-extrabold text-foreground mt-0.5">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>

                <div class="grid grid-cols-2 gap-2.5 sm:gap-3">
                    <x-ui.button :href="config('masjid.maps_url')" target="_blank" icon="map-pin" class="w-full justify-center bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs sm:text-sm px-2 sm:px-4">Buka Google Maps</x-ui.button>
                    <x-ui.button variant="outline" :href="route('kontak')" icon="mail" class="w-full justify-center rounded-xl text-xs sm:text-sm px-2 sm:px-4">Hubungi Pengurus</x-ui.button>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-border shadow-md">
                <iframe
                    src="https://www.openstreetmap.org/export/embed.html?bbox={{ config('masjid.lng') - 0.004 }},{{ config('masjid.lat') - 0.003 }},{{ config('masjid.lng') + 0.004 }},{{ config('masjid.lat') + 0.003 }}&layer=mapnik&marker={{ config('masjid.lat') }},{{ config('masjid.lng') }}"
                    class="h-80 w-full lg:h-full" style="border:0" loading="lazy" title="Peta Masjid Al-Ikhlash"></iframe>
            </div>
        </div>
    </section>
</div>
