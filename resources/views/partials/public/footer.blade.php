@php
    $m = fn ($k) => setting($k, config('masjid.'.$k));
    $socials = array_filter([
        'facebook'  => setting('facebook'),
        'instagram' => setting('instagram'),
        'youtube'   => setting('youtube'),
        'tiktok'    => setting('tiktok'),
    ]);
@endphp

<footer class="border-t border-amber-700/30 bg-gradient-to-b from-background via-stone-950/60 to-amber-950/40 bg-islamic-pattern">
    <div class="mx-auto max-w-7xl px-4 py-5 sm:py-16 sm:px-6 lg:px-8">
        {{-- Bismillah Header Accent --}}
        <div class="mb-4 sm:mb-12 text-center border-b border-amber-700/25 pb-3 sm:pb-8">
            <p class="font-amiri text-2xl font-bold tracking-wide text-amber-600 dark:text-amber-400 sm:text-3xl">
                بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ
            </p>
            <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em] text-amber-600 dark:text-amber-400">
                Pusat Ibadah, Ilmu, & Pemberdayaan Umat
            </p>
        </div>

        <div class="grid grid-cols-2 gap-8 lg:grid-cols-5">
            {{-- Identitas --}}
            <div class="col-span-2">
                <div class="flex items-center gap-3">
                    <span class="grid size-12 place-items-center rounded-2xl bg-gradient-to-br from-amber-600 via-amber-800 to-amber-950 text-amber-300 shadow-md shadow-stone-950/40 border border-amber-400/30">
                        <i data-lucide="moon-star" class="size-6 text-amber-300"></i>
                    </span>
                    <div>
                        <p class="font-jakarta text-lg font-bold tracking-tight text-foreground">{{ $m('name') }}</p>
                        <p class="text-xs text-amber-600 dark:text-amber-400 font-medium">{{ config('masjid.tagline') }}</p>
                    </div>
                </div>

                <p class="mt-4 max-w-sm text-sm leading-relaxed text-muted-foreground">
                    {{ $m('address') }}
                </p>

                <div class="mt-5 flex flex-wrap gap-2.5">
                    <a href="{{ config('masjid.maps_url') }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 rounded-xl border border-amber-500/30 bg-amber-500/10 px-3.5 py-2 text-xs font-semibold text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 transition-all">
                        <i data-lucide="map-pin" class="size-4 text-amber-500"></i>Petunjuk Google Maps
                    </a>
                    @if ($m('phone'))
                        <a href="tel:{{ $m('phone') }}" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-3.5 py-2 text-xs font-medium text-foreground hover:bg-accent transition-all">
                            <i data-lucide="phone" class="size-4 text-amber-500"></i>{{ $m('phone') }}
                        </a>
                    @endif
                </div>

                @if ($socials)
                    <div class="mt-5 flex gap-2.5">
                        @foreach ($socials as $name => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener" title="{{ ucfirst($name) }}"
                               class="grid size-10 place-items-center rounded-xl border border-amber-700/30 bg-card text-muted-foreground transition-all hover:border-amber-400/40 hover:bg-amber-500/10 hover:text-amber-500 hover:scale-105">
                                @if ($name === 'facebook')
                                    <x-icons.facebook class="size-4.5" />
                                @elseif ($name === 'instagram')
                                    <x-icons.instagram class="size-4.5" />
                                @elseif ($name === 'youtube')
                                    <x-icons.youtube class="size-4.5" />
                                @elseif ($name === 'tiktok')
                                    <x-icons.tiktok class="size-4.5" />
                                @else
                                    <i data-lucide="{{ $name }}" class="size-4.5"></i>
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Tautan --}}
            @foreach ([
                'Ibadah' => [['Jadwal Sholat', 'jadwal'], ['Jadwal Imam', 'imam'], ['Khatib Jumat', 'jumat'], ['Arah Kiblat', 'kiblat'], ['Al-Quran Digital', 'quran']],
                'Majelis & Media' => [['Kajian Rutin', 'kajian'], ['Kalender Hijriah', 'kalender'], ['Live Streaming', 'live'], ['Program Masjid', 'program'], ['Galeri Kegiatan', 'galeri']],
                'Layanan Umat' => [['Donasi Infaq', 'donasi'], ['Laporan Transparan', 'transparansi'], ['Kalkulator Zakat', 'zakat'], ['Booking Ruangan', 'booking'], ['Pasar UMKM', 'umkm']],
            ] as $group => $links)
                <div>
                    <p class="text-sm font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">{{ $group }}</p>
                    <ul class="mt-4 space-y-2.5">
                        @foreach ($links as [$label, $route])
                            <li>
                                <a href="{{ route($route) }}" wire:navigate class="text-sm text-muted-foreground transition-all hover:text-amber-600 dark:hover:text-amber-400 hover:ps-1 flex items-center gap-1.5">
                                    <i data-lucide="chevron-right" class="size-3 text-amber-500/60"></i> {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="mt-8 sm:mt-12 flex flex-col items-center justify-between gap-4 border-t border-border/80 pt-6 pb-16 lg:pb-0 text-xs text-muted-foreground sm:flex-row text-center sm:text-start">
            <p class="text-center sm:text-start">© {{ date('Y') }} {{ $m('name') }}. Seluruh Hak Cipta Dilindungi.</p>
            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-5">
                <a href="{{ route('faq') }}" wire:navigate class="hover:text-amber-500 transition-colors">Pusat Bantuan</a>
                <a href="{{ route('kontak') }}" wire:navigate class="hover:text-amber-500 transition-colors">Kontak Takmir</a>
                <a href="{{ route('login') }}" wire:navigate class="hover:text-amber-500 transition-colors flex items-center gap-1 font-semibold text-amber-600 dark:text-amber-400">
                    <i data-lucide="lock" class="size-3"></i> Login Pengurus
                </a>
            </div>
        </div>
    </div>
</footer>
