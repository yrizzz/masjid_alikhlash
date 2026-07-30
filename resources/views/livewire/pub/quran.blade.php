<div x-data="quranReader()" class="min-h-screen">
    <x-page-hero title="Al-Quran Digital" icon="book-open-text" compact
                 subtitle="Baca, dengarkan, tandai ayat, beri highlight, dan simpan catatan pribadi Anda." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-6 sm:px-6 lg:px-8">
        @if (! $detail)
            <x-empty-state icon="wifi-off" title="Data Al-Quran belum tersedia"
                           message="Teks Al-Quran diunduh sekali lalu tersimpan permanen. Sambungkan internet, lalu muat ulang halaman ini." />
        @else
            <div class="grid gap-6 lg:grid-cols-[19rem_1fr] w-full min-w-0 items-start">
                
                {{-- ══════════════ DAFTAR SURAH (SIDEBAR) ══════════════ --}}
                <aside class="w-full min-w-0 lg:sticky lg:top-24 lg:max-h-[calc(100vh-7rem)] lg:self-start flex flex-col gap-3">
                    @if ($lastRead)
                        <a href="{{ route('quran', $lastRead->surah) }}" wire:navigate
                           class="group relative overflow-hidden rounded-2xl border border-primary/30 bg-gradient-to-r from-primary/10 via-primary/5 to-transparent p-3.5 transition-all hover:border-primary/50 hover:shadow-md">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-xs font-semibold text-primary">
                                    <svg class="size-4 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <span>Terakhir Dibaca</span>
                                </div>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-primary/15 text-primary">Lanjut</span>
                            </div>
                            <p class="mt-1 text-sm font-bold text-foreground group-hover:text-primary transition-colors">
                                {{ $lastRead->surah_name }} : Ayat {{ $lastRead->ayah }}
                            </p>
                        </a>
                    @endif

                    {{-- Search Surah --}}
                    <div class="relative">
                        <svg class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                        </svg>
                        <input x-model="search" type="search" placeholder="Cari surah (misal: Yasin, Al-Kahf)…"
                               class="h-10 w-full rounded-xl border border-input bg-card ps-9 pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all placeholder:text-muted-foreground/70" />
                    </div>

                    {{-- List 114 Surah (Client-Side Instant Filter) --}}
                    <div class="max-h-[26rem] lg:max-h-[calc(100vh-19rem)] space-y-1 overflow-y-auto rounded-2xl border border-border/80 bg-card/80 backdrop-blur-sm p-2 shadow-sm">
                        @foreach ($surahs as $s)
                            <button wire:click="open({{ $s['nomor'] }})" wire:key="s{{ $s['nomor'] }}"
                                    x-show="search === '' || ('{{ strtolower($s['namaLatin']) }} {{ strtolower($s['arti']) }} {{ $s['nomor'] }}').includes(search.toLowerCase())"
                                    class="flex w-full items-center gap-3 rounded-xl p-2.5 text-start transition-all duration-150 {{ $surah === $s['nomor'] ? 'bg-primary/15 border border-primary/30 shadow-sm' : 'hover:bg-accent/70' }}">
                                <span class="grid size-8 shrink-0 place-items-center rounded-lg text-xs font-bold {{ $surah === $s['nomor'] ? 'bg-primary text-primary-foreground shadow-sm' : 'bg-muted/80 text-muted-foreground' }}">
                                    {{ $s['nomor'] }}
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-semibold {{ $surah === $s['nomor'] ? 'text-primary' : 'text-foreground' }}">{{ $s['namaLatin'] }}</span>
                                    <span class="block truncate text-xs text-muted-foreground">{{ $s['arti'] }} · {{ $s['jumlahAyat'] }} ayat</span>
                                </span>
                                <span class="shrink-0 text-base font-serif text-foreground/80" style="font-family: 'Amiri', serif">{{ $s['nama'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </aside>

                {{-- ══════════════ ISI SURAH & AYAT ══════════════ --}}
                <main class="w-full min-w-0 space-y-6">
                    
                    {{-- Banner Header Surah --}}
                    <div class="relative overflow-hidden rounded-3xl border border-border/80 bg-gradient-to-br from-primary/15 via-card to-card p-6 sm:p-8 text-center shadow-sm">
                        <div class="absolute -top-12 -right-12 size-40 rounded-full bg-primary/10 blur-2xl pointer-events-none"></div>
                        <div class="absolute -bottom-12 -left-12 size-40 rounded-full bg-amber-500/10 blur-2xl pointer-events-none"></div>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20 mb-3">
                            Surah Ke-{{ $detail['nomor'] }} · {{ $detail['tempatTurun'] }}
                        </span>

                        <p class="text-4xl sm:text-5xl font-bold text-foreground py-1" style="font-family: 'Amiri', serif">{{ $detail['nama'] }}</p>
                        <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground">{{ $detail['namaLatin'] }}</h1>
                        <p class="mt-1 text-sm font-medium text-muted-foreground">"{{ $detail['arti'] }}" · {{ $detail['jumlahAyat'] }} Ayat</p>

                        {{-- Action Header Bar --}}
                        <div class="mt-6 flex flex-wrap items-center justify-center gap-3 border-t border-border/60 pt-5">
                            @if (! empty($detail['audioFull']['05'] ?? $detail['audioFull']['01'] ?? null))
                                <button @click="playSurah('{{ $detail['audioFull']['05'] ?? $detail['audioFull']['01'] }}')"
                                        class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-xs font-semibold text-primary-foreground shadow-md transition-all hover:bg-primary/90 hover:scale-[1.02] active:scale-[0.98]">
                                    <template x-if="playMode === 'surah' && isPlaying">
                                        <svg class="size-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                    </template>
                                    <template x-if="!(playMode === 'surah' && isPlaying)">
                                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                    </template>
                                    <span x-text="playMode === 'surah' && isPlaying ? 'Jeda Audio Surah' : 'Putar Audio Full Surah'"></span>
                                </button>
                            @endif

                            {{-- Jump to Ayah --}}
                            <div class="flex items-center gap-1.5 rounded-xl border border-input bg-background/80 px-2.5 py-1 text-xs">
                                <span class="text-muted-foreground font-medium">Lompat Ayat:</span>
                                <input x-model="jumpAyah" @keyup.enter="onJump()" type="number" min="1" max="{{ $detail['jumlahAyat'] }}" placeholder="1-{{ $detail['jumlahAyat'] }}"
                                       class="w-14 bg-transparent text-center font-bold focus:outline-none" />
                                <button @click="onJump()" class="rounded-md bg-muted px-2 py-1 font-semibold hover:bg-accent text-foreground">Go</button>
                            </div>
                        </div>
                    </div>

                    {{-- Toolbar Kontrol Tampilan Reading --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-border/80 bg-card p-3 shadow-sm">
                        <div class="flex flex-wrap items-center gap-2">
                            <button wire:click="$toggle('showTranslation')"
                                    class="rounded-xl border px-3 py-1.5 text-xs font-semibold transition-all shadow-2xs {{ $showTranslation ? 'border-primary bg-primary/10 text-primary' : 'border-border text-muted-foreground hover:bg-accent' }}">
                                Terjemahan
                            </button>
                            <button wire:click="$toggle('showLatin')"
                                    class="rounded-xl border px-3 py-1.5 text-xs font-semibold transition-all shadow-2xs {{ $showLatin ? 'border-primary bg-primary/10 text-primary' : 'border-border text-muted-foreground hover:bg-accent' }}">
                                Transliterasi (Latin)
                            </button>
                            <button wire:click="$toggle('showTafsir')"
                                    class="rounded-xl border px-3 py-1.5 text-xs font-semibold transition-all shadow-2xs {{ $showTafsir ? 'border-primary bg-primary/10 text-primary' : 'border-border text-muted-foreground hover:bg-accent' }}">
                                Tafsir Ayat
                            </button>
                        </div>

                        {{-- Font Size Controls --}}
                        <div class="flex items-center gap-1.5 rounded-xl border border-border bg-muted/40 p-1">
                            <span class="text-[11px] font-semibold text-muted-foreground px-2">Ukuran Teks Arab:</span>
                            <template x-for="sz in ['sm', 'md', 'lg', 'xl']" :key="sz">
                                <button @click="fontSize = sz"
                                        :class="fontSize === sz ? 'bg-background text-primary font-bold shadow-xs' : 'text-muted-foreground hover:text-foreground'"
                                        class="rounded-lg px-2.5 py-1 text-xs uppercase font-medium transition-all"
                                        x-text="sz">
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Kaligrafi Bismillah --}}
                    @if ($surah !== 1 && $surah !== 9)
                        <div class="my-6 rounded-2xl border border-amber-500/20 bg-amber-500/5 p-6 text-center shadow-xs">
                            <p class="bismillah-text">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                        </div>
                    @endif

                    {{-- ══════════════ DAFTAR AYAT ══════════════ --}}
                    <div class="space-y-4">
                        @foreach ($detail['ayat'] ?? [] as $ayat)
                            @php
                                $no = $ayat['nomorAyat'];
                                $isBookmarked = ($marks['bookmark'] ?? collect())->contains('ayah', $no);
                                $isHighlighted = ($marks['highlight'] ?? collect())->contains('ayah', $no);
                                $note = ($marks['note'] ?? collect())->firstWhere('ayah', $no);
                                $audioAyahUrl = $ayat['audio']['05'] ?? $ayat['audio']['01'] ?? '';
                            @endphp

                            <div id="ayah-{{ $no }}"
                                 wire:key="a{{ $no }}"
                                 :class="{ 'verse-playing-glow bg-amber-500/10 dark:bg-amber-500/15 border-amber-500/50': activeAyah === {{ $no }} }"
                                 class="group relative rounded-3xl border p-5 sm:p-6 transition-all duration-200 {{ $isHighlighted ? 'border-amber-400/40 bg-amber-400/8' : 'border-border/80 bg-card hover:border-border' }}">
                                
                                {{-- Bar atas ayat: Nomor & Toolbar aksi --}}
                                <div class="flex items-center justify-between gap-3 border-b border-border/50 pb-3 mb-4">
                                    <div class="flex items-center gap-2">
                                        <div class="relative flex size-9 shrink-0 items-center justify-center rounded-xl border border-primary/30 bg-primary/10 text-xs font-bold text-primary shadow-2xs">
                                            {{ $no }}
                                        </div>
                                        <span class="text-xs font-medium text-muted-foreground hidden sm:inline">QS. {{ $detail['namaLatin'] }} : {{ $no }}</span>
                                    </div>

                                    {{-- Toolbar Tombol Aksi Ayat (Inline SVGs untuk Performa Maksimal) --}}
                                    <div class="flex items-center gap-1">
                                        
                                        {{-- Putar Audio Ayat --}}
                                        @if (! empty($audioAyahUrl))
                                            <button @click="playAyah({{ $no }}, '{{ $audioAyahUrl }}')"
                                                    data-ayah-audio-btn="{{ $no }}"
                                                    data-audio-url="{{ $audioAyahUrl }}"
                                                    title="Putar Audio Ayat {{ $no }}"
                                                    :class="activeAyah === {{ $no }} && isPlaying ? 'bg-primary text-primary-foreground shadow-xs' : 'text-muted-foreground hover:bg-accent hover:text-foreground'"
                                                    class="rounded-xl p-2 transition-all">
                                                <template x-if="activeAyah === {{ $no }} && isPlaying">
                                                    <svg class="size-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                                </template>
                                                <template x-if="!(activeAyah === {{ $no }} && isPlaying)">
                                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                                </template>
                                            </button>
                                        @endif

                                        {{-- Copy Ayat --}}
                                        <button @click="copyAyah({{ $no }}, @js($ayat['teksArab']), @js($ayat['teksLatin']), @js($ayat['teksIndonesia']))"
                                                title="Salin Ayat & Terjemahan"
                                                class="rounded-xl p-2 text-muted-foreground hover:bg-accent hover:text-foreground transition-all">
                                            <template x-if="copiedAyah === {{ $no }}">
                                                <svg class="size-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                                            </template>
                                            <template x-if="copiedAyah !== {{ $no }}">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                                            </template>
                                        </button>

                                        {{-- Bookmark --}}
                                        <button wire:click="toggleBookmark({{ $no }})" title="Tandai Bookmark"
                                                class="rounded-xl p-2 transition-all {{ $isBookmarked ? 'text-primary bg-primary/10 font-bold' : 'text-muted-foreground hover:bg-accent hover:text-foreground' }}">
                                            <svg class="size-4" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                        </button>

                                        {{-- Highlight --}}
                                        <button wire:click="highlight({{ $no }})" title="Beri Highlight"
                                                class="rounded-xl p-2 transition-all {{ $isHighlighted ? 'text-amber-500 bg-amber-500/10' : 'text-muted-foreground hover:bg-accent hover:text-foreground' }}">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 11-6 6v3h3l6-6m-3-3 3-3 6 6-3 3m-3-3 6-6a2 2 0 0 0-3-3l-6 6"/></svg>
                                        </button>

                                        {{-- Catatan Pribadi --}}
                                        <button wire:click="openNote({{ $no }})" title="Catatan Pribadi"
                                                class="rounded-xl p-2 transition-all {{ $note ? 'text-primary bg-primary/10' : 'text-muted-foreground hover:bg-accent hover:text-foreground' }}">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15.5 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3z"/><path d="M15 3v6h6"/></svg>
                                        </button>

                                        {{-- Tandai Terakhir Dibaca --}}
                                        <button wire:click="markLastRead({{ $no }})" title="Tandai Terakhir Dibaca"
                                                class="rounded-xl p-2 text-muted-foreground hover:bg-accent hover:text-primary transition-all">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Teks Arab Ayat --}}
                                <div class="py-2">
                                    <p class="quran-arabic" :class="'quran-arabic-' + fontSize">{{ $ayat['teksArab'] }}</p>
                                </div>

                                {{-- Transliterasi Latin --}}
                                @if ($showLatin)
                                    <p class="mt-3 text-sm italic text-muted-foreground/90 font-sans tracking-wide leading-relaxed">{{ $ayat['teksLatin'] }}</p>
                                @endif

                                {{-- Terjemahan Indonesia --}}
                                @if ($showTranslation)
                                    <p class="mt-2 text-sm leading-relaxed text-foreground/90">{{ $ayat['teksIndonesia'] }}</p>
                                @endif

                                {{-- Tafsir Ringkas --}}
                                @if ($showTafsir && ! empty($tafsir[$no]['teks']))
                                    <div class="mt-4 rounded-2xl bg-muted/60 p-4 border border-border/50">
                                        <p class="text-[11px] font-bold uppercase tracking-wider text-primary">Tafsir Ayat {{ $no }}</p>
                                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $tafsir[$no]['teks'] }}</p>
                                    </div>
                                @endif

                                {{-- Catatan Pribadi --}}
                                @if ($note?->note)
                                    <div class="mt-3 rounded-2xl border border-primary/30 bg-primary/5 p-3.5">
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-primary mb-1">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15.5 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3z"/><path d="M15 3v6h6"/></svg>
                                            <span>Catatan Anda</span>
                                        </div>
                                        <p class="text-sm text-foreground/90 leading-relaxed">{{ $note->note }}</p>
                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>

                    {{-- Navigasi Surah Sebelumnya / Berikutnya --}}
                    <div class="mt-8 flex items-center justify-between gap-4 pt-4 border-t border-border/60">
                        @if ($surah > 1)
                            <button wire:click="open({{ $surah - 1 }})" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-xs font-semibold text-foreground shadow-2xs hover:bg-accent transition-all">
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                                <span>Surah Sebelumnya</span>
                            </button>
                        @else <div></div> @endif

                        @if ($surah < 114)
                            <button wire:click="open({{ $surah + 1 }})" class="inline-flex items-center gap-2 rounded-xl border border-border bg-card px-4 py-2.5 text-xs font-semibold text-foreground shadow-2xs hover:bg-accent transition-all">
                                <span>Surah Berikutnya</span>
                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        @endif
                    </div>

                </main>
            </div>

            {{-- ══════════════ MODAL CATATAN PRIBADI ══════════════ --}}
            @if ($noteAyah)
                <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" wire:click="$set('noteAyah', null)"></div>
                    <div class="relative w-full max-w-md rounded-3xl border border-border bg-card p-6 shadow-2xl space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="font-extrabold text-foreground flex items-center gap-2">
                                <svg class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15.5 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8.5L15.5 3z"/><path d="M15 3v6h6"/></svg>
                                <span>Catatan Ayat {{ $noteAyah }}</span>
                            </h3>
                            <button wire:click="$set('noteAyah', null)" class="text-muted-foreground hover:text-foreground">
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>

                        <textarea wire:model="noteText" rows="5" placeholder="Tulis renungan, pelajaran, atau catatan pribadi Anda…"
                                  class="w-full rounded-xl border border-input bg-background p-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50"></textarea>
                        
                        <div class="flex justify-end gap-2 pt-2">
                            <x-ui.button variant="outline" wire:click="$set('noteAyah', null)">Batal</x-ui.button>
                            <x-ui.button wire:click="saveNote" icon="save">Simpan Catatan</x-ui.button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ══════════════ STICKY FLOATING AUDIO PLAYER (SINGLE INSTANCE) ══════════════ --}}
            <div x-show="audioUrl && (isPlaying || activeAyah || playMode === 'surah')"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-8"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-8"
                 class="fixed bottom-4 inset-x-4 max-w-3xl mx-auto z-[90] rounded-2xl border border-primary/30 bg-background/95 backdrop-blur-md p-3 sm:p-4 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-3">
                
                {{-- Play Control & Title --}}
                <div class="flex items-center gap-3 w-full sm:w-auto min-w-0">
                    <button @click="togglePlay()" class="grid size-10 shrink-0 place-items-center rounded-xl bg-primary text-primary-foreground shadow-md transition-transform hover:scale-105 active:scale-95">
                        <template x-if="isPlaying">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                        </template>
                        <template x-if="!isPlaying">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                        </template>
                    </button>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="inline-block size-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <p class="truncate text-xs font-bold text-primary uppercase tracking-wide">
                                <span x-text="playMode === 'surah' ? 'Audio Surah Full' : 'Ayat ' + activeAyah"></span>
                            </p>
                        </div>
                        <p class="truncate text-sm font-semibold text-foreground">
                            QS. {{ $detail['namaLatin'] }} <span x-show="activeAyah" x-text="': Ayat ' + activeAyah"></span>
                        </p>
                    </div>
                </div>

                {{-- Scrubber & Time --}}
                <div class="flex items-center gap-3 w-full sm:w-1/2">
                    {{-- Prev / Next (Only for Ayah mode) --}}
                    <div x-show="playMode === 'ayah'" class="flex items-center gap-1">
                        <button @click="playPrevAyah()" title="Ayat Sebelumnya" class="rounded-lg p-1.5 text-muted-foreground hover:bg-accent hover:text-foreground">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
                        </button>
                        <button @click="playNextAyah()" title="Ayat Berikutnya" class="rounded-lg p-1.5 text-muted-foreground hover:bg-accent hover:text-foreground">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                        </button>
                    </div>

                    <span class="text-[11px] font-mono font-medium text-muted-foreground" x-text="formatTime(currentTime)">00:00</span>
                    <input type="range" min="0" :max="duration || 100" :value="currentTime"
                           @input="audio.currentTime = $event.target.value"
                           class="h-1.5 flex-1 cursor-pointer appearance-none rounded-lg bg-muted accent-primary" />
                    <span class="text-[11px] font-mono font-medium text-muted-foreground" x-text="formatTime(duration)">00:00</span>
                </div>

                {{-- Stop Button --}}
                <button @click="stopAudio()" title="Tutup Player" class="rounded-xl p-2 text-muted-foreground hover:bg-destructive/10 hover:text-destructive transition-colors shrink-0">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

        @endif
    </div>
</div>

<script>
function quranReader() {
    return {
        search: '',
        fontSize: localStorage.getItem('ak_quran_fontSize') || 'md',
        jumpAyah: '',
        isPlaying: false,
        activeAyah: null,
        playMode: null, // 'ayah' | 'surah'
        audioUrl: '',
        duration: 0,
        currentTime: 0,
        autoAdvance: true,
        copiedAyah: null,
        audio: null,

        init() {
            this.audio = new Audio();
            this.audio.addEventListener('timeupdate', () => {
                this.currentTime = this.audio.currentTime || 0;
                this.duration = this.audio.duration || 0;
            });
            this.audio.addEventListener('ended', () => {
                if (this.playMode === 'ayah' && this.autoAdvance && this.activeAyah) {
                    this.playNextAyah();
                } else {
                    this.isPlaying = false;
                    this.activeAyah = null;
                    this.playMode = null;
                }
            });
            this.$watch('fontSize', (v) => localStorage.setItem('ak_quran_fontSize', v));
        },

        playAyah(no, url) {
            if (!url) return;
            if (this.activeAyah === no && this.playMode === 'ayah') {
                this.togglePlay();
                return;
            }
            this.activeAyah = no;
            this.playMode = 'ayah';
            this.audioUrl = url;
            this.audio.src = url;
            this.audio.play();
            this.isPlaying = true;
            this.scrollToAyah(no);
        },

        playSurah(url) {
            if (!url) return;
            if (this.playMode === 'surah') {
                this.togglePlay();
                return;
            }
            this.activeAyah = null;
            this.playMode = 'surah';
            this.audioUrl = url;
            this.audio.src = url;
            this.audio.play();
            this.isPlaying = true;
        },

        togglePlay() {
            if (!this.audio.src) return;
            if (this.isPlaying) {
                this.audio.pause();
                this.isPlaying = false;
            } else {
                this.audio.play();
                this.isPlaying = true;
            }
        },

        stopAudio() {
            this.audio.pause();
            this.audio.currentTime = 0;
            this.isPlaying = false;
            this.activeAyah = null;
            this.playMode = null;
        },

        playNextAyah() {
            if (!this.activeAyah) return;
            const nextNo = this.activeAyah + 1;
            const nextBtn = document.querySelector(`[data-ayah-audio-btn="${nextNo}"]`);
            if (nextBtn) {
                const nextUrl = nextBtn.dataset.audioUrl;
                if (nextUrl) {
                    this.playAyah(nextNo, nextUrl);
                } else {
                    this.stopAudio();
                }
            } else {
                this.stopAudio();
            }
        },

        playPrevAyah() {
            if (!this.activeAyah || this.activeAyah <= 1) return;
            const prevNo = this.activeAyah - 1;
            const prevBtn = document.querySelector(`[data-ayah-audio-btn="${prevNo}"]`);
            if (prevBtn) {
                const prevUrl = prevBtn.dataset.audioUrl;
                if (prevUrl) {
                    this.playAyah(prevNo, prevUrl);
                }
            }
        },

        scrollToAyah(no) {
            const el = document.getElementById(`ayah-${no}`);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },

        onJump() {
            const no = parseInt(this.jumpAyah);
            if (no && !isNaN(no)) {
                this.scrollToAyah(no);
            }
        },

        copyAyah(no, arabic, latin, id) {
            const text = `${arabic}\n\n${latin ? latin + '\n' : ''}"${id}"\n(QS. {{ $detail['namaLatin'] ?? '' }} : ${no})`;
            navigator.clipboard.writeText(text).then(() => {
                this.copiedAyah = no;
                setTimeout(() => { if (this.copiedAyah === no) this.copiedAyah = null; }, 2000);
                if (window.toast) window.toast('Teks ayat berhasil disalin ke clipboard.', { variant: 'success' });
            });
        },

        formatTime(s) {
            if (!s || isNaN(s)) return '00:00';
            const m = Math.floor(s / 60);
            const sec = Math.floor(s % 60);
            return `${String(m).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;
        }
    };
}
</script>
