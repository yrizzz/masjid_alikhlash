@php
    $heroImage = $banner?->image ? img_url($banner->image) : route('placeholder', ['seed' => 'masjid-alikhlash']);
@endphp

<div class="font-jakarta bg-background text-foreground antialiased selection:bg-emerald-500 selection:text-white">
    {{-- ══════════════ HERO SECTION — REDESIGN ══════════════ --}}
    <section class="hero-shell relative overflow-hidden">
        {{-- Full bleed background photo --}}
        <div class="hero-bg" style="background-image: url('{{ $heroImage }}')"></div>

        {{-- Multi-layer overlay: coklat tua gelap + sorot emas hangat --}}
        <div class="absolute inset-0 z-[-1]"
             style="background:
                linear-gradient(180deg, rgba(22,8,2,.75) 0%, rgba(28,10,3,.42) 30%, rgba(12,4,1,.97) 100%),
                radial-gradient(ellipse 80% 55% at 50% 0%, rgba(140,75,25,.38) 0%, transparent 65%),
                radial-gradient(ellipse 40% 40% at 80% 20%, rgba(245,158,11,.25) 0%, transparent 55%);"></div>

        {{-- SVG Islamic arch/dome silhouette at bottom for depth — coklat walnut --}}
        <div class="absolute bottom-0 inset-x-0 pointer-events-none z-0 overflow-hidden">
            <svg viewBox="0 0 1440 200" xmlns="http://www.w3.org/2000/svg" class="w-full" preserveAspectRatio="none">
                <path d="M0,200 L0,160 Q180,60 360,120 Q480,160 560,100 Q640,40 720,80 Q800,120 880,80 Q960,40 1040,100 Q1120,160 1200,120 Q1320,60 1440,160 L1440,200 Z"
                      fill="rgba(15,6,2,0.95)"/>
                <!-- Dome silhouette center -->
                <ellipse cx="720" cy="85" rx="80" ry="55" fill="rgba(20,8,2,0.80)"/>
                <rect x="700" y="20" width="40" height="70" rx="20" fill="rgba(25,10,3,0.75)"/>
                <circle cx="720" cy="18" r="8" fill="rgba(245,158,11,0.55)"/>
                <!-- Side minarets -->
                <rect x="200" y="100" width="18" height="65" rx="9" fill="rgba(20,8,2,0.65)"/>
                <circle cx="209" cy="98" r="5" fill="rgba(245,158,11,0.45)"/>
                <rect x="1222" y="100" width="18" height="65" rx="9" fill="rgba(20,8,2,0.65)"/>
                <circle cx="1231" cy="98" r="5" fill="rgba(245,158,11,0.45)"/>
            </svg>
        </div>

        {{-- Floating star particles (CSS only) --}}
        <div class="absolute inset-0 z-0 pointer-events-none bg-islamic-stars opacity-25"></div>

        {{-- MAIN CONTENT: Centered layout --}}
        <div class="relative z-10 mx-auto flex w-full max-w-5xl flex-col items-center justify-center min-h-screen px-4 pb-36 pt-28 text-center sm:px-6 lg:px-8">

            {{-- Arabic verse ornament --}}
            <div class="mb-6 max-w-xl mx-auto">
                <p class="font-amiri text-2xl sm:text-3xl tracking-wide text-amber-300/90 drop-shadow-md">
                    إِنَّمَا يَعْمُرُ مَسَاجِدَ اللَّهِ مَنْ آمَنَ بِاللَّهِ
                </p>
                <p class="mt-1 text-[0.68rem] font-semibold uppercase tracking-[0.25em] text-amber-400/80">
                    QS. At-Taubah : 18
                </p>
                <p class="mt-1.5 text-xs sm:text-sm text-amber-100/75 italic leading-relaxed max-w-lg mx-auto font-medium">
                    "Hanya yang memakmurkan masjid-masjid Allah ialah orang yang beriman kepada Allah & hari kemudian."
                </p>
            </div>

            {{-- Hijri badge --}}
            <div class="inline-flex items-center gap-2.5 rounded-full glass-mosque-gold px-4 py-2 text-xs font-semibold text-amber-200 border border-amber-400/40 shadow-lg backdrop-blur-xl mb-7">
                <span class="relative flex size-2">
                    <span class="absolute inline-flex size-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex size-2 rounded-full bg-amber-400"></span>
                </span>
                <i data-lucide="moon-star" class="size-3.5 text-amber-300"></i>
                <span>{{ $hijri['day_name'] }}, {{ $hijri['formatted'] }}</span>
                @if ($holiday)
                    <span class="border-s border-amber-400/40 ps-2.5 text-amber-300 font-bold">{{ $holiday }}</span>
                @endif
            </div>

            {{-- Mosque name & tagline --}}
            <h1 class="text-3xl xs:text-4xl sm:text-6xl lg:text-7xl font-extrabold leading-[1.12] tracking-tight text-white drop-shadow-xl max-w-3xl mx-auto">
                {!! nl2br(e(setting('name', config('masjid.name')))) !!}
            </h1>
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-emerald-100/90 sm:text-base lg:text-lg">
                {{ setting('tagline', config('masjid.tagline')) }}
                <span class="font-semibold text-amber-300">·&nbsp;{{ config('masjid.village') }}</span>,&nbsp;{{ config('masjid.city') }}.
            </p>

            {{-- CTA buttons --}}
            <div class="mt-9 flex flex-wrap justify-center items-center gap-3">
                @foreach (config('masjid.hero_actions') as $action)
                    <a href="{{ route($action['route']) }}" wire:navigate
                       class="inline-flex items-center gap-2.5 rounded-2xl px-6 py-3.5 text-sm font-bold transition-all duration-300 hover:-translate-y-0.5 active:scale-95
                              {{ ($action['primary'] ?? false)
                                  ? 'bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600 text-slate-950 shadow-xl shadow-amber-500/30 hover:shadow-2xl hover:shadow-amber-500/45 hover:brightness-110 border border-amber-300/50'
                                  : 'glass text-white hover:bg-white/20 hover:border-amber-400/40 shadow-lg' }}">
                        <i data-lucide="{{ $action['icon'] }}" class="size-5 {{ ($action['primary'] ?? false) ? 'text-slate-950' : 'text-amber-300' }}"></i>
                        <span>{{ $action['label'] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Live indicator --}}
            @if ($live && $live->status === 'live')
                <a href="{{ route('live') }}" wire:navigate class="mt-5 inline-flex items-center gap-3 rounded-full border border-rose-500/50 bg-rose-950/60 px-5 py-2.5 text-sm font-bold text-white shadow-xl backdrop-blur-xl hover:bg-rose-900/80 transition-all">
                    <span class="relative flex size-2.5">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-rose-400"></span>
                        <span class="relative inline-flex size-2.5 rounded-full bg-rose-500"></span>
                    </span>
                    SEDANG LIVE · {{ Str::limit($live->title, 38) }}
                </a>
            @endif
        </div>

        {{-- ═══ PRAYER TIME FLOATING BAR — pinned to hero bottom ═══ --}}
        <div class="absolute bottom-0 inset-x-0 z-20 overflow-hidden w-full max-w-full">
            {{-- Running text strip --}}
            @if ($running->isNotEmpty())
                <div class="border-t border-amber-400/20 bg-slate-950/85 py-2.5 backdrop-blur-xl overflow-hidden w-full max-w-full">
                    <div class="marquee text-sm text-emerald-100/90 font-medium px-4">
                        @for ($i = 0; $i < 2; $i++)
                            <div aria-hidden="{{ $i ? 'true' : 'false' }}">
                                @foreach ($running as $text)
                                    <span class="flex items-center gap-3 whitespace-nowrap">
                                        <i data-lucide="sparkles" class="size-4 text-amber-400"></i>
                                        <span>{{ $text }}</span>
                                    </span>
                                @endforeach
                            </div>
                        @endfor
                    </div>
                </div>
            @endif

            {{-- Prayer time floating panel --}}
            <div class="border-t border-amber-700/30 bg-stone-950/92 backdrop-blur-2xl overflow-hidden w-full max-w-full">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col gap-0 sm:flex-row sm:items-stretch sm:divide-x sm:divide-white/10">

                        {{-- Countdown block --}}
                        <div class="flex items-center gap-5 py-4 sm:pe-8 sm:min-w-[18rem]">
                            <div x-data="countdown({{ $prayer['seconds_left'] }})" class="flex items-center gap-1.5">
                                {{-- H --}}
                                <div class="text-center">
                                    <span class="font-outfit text-3xl font-extrabold tabular-nums text-white" x-text="parts.h"></span>
                                    <p class="text-[0.6rem] font-bold uppercase tracking-widest text-amber-400/60">Jam</p>
                                </div>
                                <span class="font-outfit text-2xl font-light text-white/40 mb-3">:</span>
                                {{-- M --}}
                                <div class="text-center">
                                    <span class="font-outfit text-3xl font-extrabold tabular-nums text-white" x-text="parts.m"></span>
                                    <p class="text-[0.6rem] font-bold uppercase tracking-widest text-amber-400/60">Menit</p>
                                </div>
                                <span class="font-outfit text-2xl font-light text-white/40 mb-3">:</span>
                                {{-- S --}}
                                <div class="text-center">
                                    <span class="font-outfit text-3xl font-extrabold tabular-nums text-amber-400" x-text="parts.s"></span>
                                    <p class="text-[0.6rem] font-bold uppercase tracking-widest text-amber-400/60">Detik</p>
                                </div>
                            </div>
                            <div class="border-s border-amber-700/30 ps-5">
                                <p class="text-[0.65rem] font-bold uppercase tracking-[0.15em] text-amber-400/70">Menuju Adzan</p>
                                <p class="mt-0.5 text-xl font-extrabold text-white font-jakarta leading-none">{{ $prayer['next_label'] }}</p>
                                <p class="mt-0.5 text-xs font-semibold text-amber-300">Pukul {{ $prayer['next_time']->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        {{-- 5 waktu sholat --}}
                        <div class="flex flex-1 items-center justify-between gap-0 overflow-x-auto py-4 sm:divide-x sm:divide-amber-700/20 sm:px-6 scrollbar-none">
                            @foreach ($prayer['times'] as $key => $time)
                                @php $isActive = $prayer['current'] === $key; @endphp
                                <div class="prayer-pill flex-1 min-w-[4rem] {{ $isActive ? 'data-[state=active]' : '' }}"
                                     data-state="{{ $isActive ? 'active' : '' }}">
                                    <span class="text-[0.65rem] font-bold uppercase tracking-wider {{ $isActive ? 'text-amber-300' : 'text-stone-400/70' }}">
                                        {{ \App\Services\PrayerTimeService::PRAYERS[$key] }}
                                    </span>
                                    <span class="font-outfit text-base font-extrabold tabular-nums {{ $isActive ? 'text-white' : ($time->isPast() ? 'text-white/40' : 'text-white/90') }}">
                                        {{ $time->format('H:i') }}
                                    </span>
                                    {{-- mini progress bar --}}
                                    <div class="h-1 w-full overflow-hidden rounded-full {{ $isActive ? 'bg-emerald-500/30' : 'bg-white/10' }}">
                                        @if ($isActive)
                                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-amber-400 transition-all duration-1000"
                                                 style="width: {{ $prayer['progress'] }}%"></div>
                                        @elseif ($time->isPast())
                                            <div class="h-full w-full rounded-full bg-white/20"></div>
                                        @endif
                                    </div>
                                    @if ($isActive)
                                        <span class="text-[0.58rem] font-bold text-emerald-300 uppercase tracking-wider">Sekarang</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Iqomah info + link jadwal --}}
                        <div class="flex flex-row items-center justify-between sm:flex-col sm:justify-center gap-2 py-2.5 sm:py-4 border-t border-white/10 sm:border-t-0 sm:ps-6 sm:min-w-[9.5rem] w-full sm:w-auto">
                            @if ($prayer['in_iqomah'])
                                <div class="flex items-center gap-1.5 text-amber-300 text-xs font-bold">
                                    <i data-lucide="bell-ring" class="size-3.5 animate-pulse"></i>
                                    <span>Iqomah {{ $prayer['iqomah_at']->format('H:i') }}</span>
                                </div>
                            @else
                                <div class="text-[0.7rem] sm:text-xs text-stone-400/80 font-medium flex items-center gap-1">
                                    <i data-lucide="globe" class="size-3 inline text-amber-400/80"></i>
                                    <span>Waktu WIB (Kemenag)</span>
                                </div>
                            @endif
                            <a href="{{ route('jadwal') }}" wire:navigate
                               class="inline-flex items-center gap-1 rounded-full bg-amber-400/15 px-2.5 py-1 text-[0.7rem] sm:text-xs font-bold text-amber-300 hover:bg-amber-400/25 transition-all">
                                <i data-lucide="calendar-days" class="size-3"></i>
                                <span>Jadwal Bulanan</span>
                                <i data-lucide="chevron-right" class="size-3 ms-0.5"></i>
                            </a>
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
