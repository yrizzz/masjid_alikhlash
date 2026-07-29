<div>
    <x-page-hero title="Al-Quran Digital" icon="book-open-text" compact
                 subtitle="Baca, dengarkan, tandai ayat, beri highlight, dan simpan catatan pribadi Anda." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-8 sm:px-6 lg:px-8">
        @if (! $detail)
            <x-empty-state icon="wifi-off" title="Data Al-Quran belum tersedia"
                           message="Teks Al-Quran diunduh sekali lalu tersimpan permanen. Sambungkan internet, lalu muat ulang halaman ini." />
        @else
            <div class="grid gap-6 lg:grid-cols-[18rem_1fr] w-full min-w-0">
                {{-- Daftar surah --}}
                <aside class="w-full min-w-0 lg:sticky lg:top-24 lg:max-h-[calc(100vh-8rem)] lg:self-start lg:overflow-hidden">
                    @if ($lastRead)
                        <a href="{{ route('quran', $lastRead->surah) }}" wire:navigate
                           class="mb-3 block rounded-2xl border border-primary/25 bg-primary/5 p-4">
                            <p class="flex items-center gap-2 text-xs font-semibold text-primary">
                                <i data-lucide="book-open-text" class="size-3.5"></i>Terakhir dibaca
                            </p>
                            <p class="mt-1 font-semibold">{{ $lastRead->surah_name }} : {{ $lastRead->ayah }}</p>
                        </a>
                    @endif

                    <div class="relative">
                        <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground"></i>
                        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Cari surah…"
                               class="h-10 w-full rounded-xl border border-input bg-background ps-9 pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                    </div>

                    <div class="mt-3 max-h-[24rem] space-y-1 overflow-y-auto rounded-2xl border border-border bg-card p-2 lg:max-h-[calc(100vh-20rem)]">
                        @foreach ($surahs as $s)
                            <button wire:click="open({{ $s['nomor'] }})" wire:key="s{{ $s['nomor'] }}"
                                    class="flex w-full items-center gap-3 rounded-xl p-2.5 text-start transition-colors {{ $surah === $s['nomor'] ? 'bg-primary/10' : 'hover:bg-accent' }}">
                                <span class="grid size-8 shrink-0 place-items-center rounded-lg text-xs font-bold {{ $surah === $s['nomor'] ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground' }}">
                                    {{ $s['nomor'] }}
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-medium">{{ $s['namaLatin'] }}</span>
                                    <span class="block truncate text-xs text-muted-foreground">{{ $s['arti'] }} · {{ $s['jumlahAyat'] }} ayat</span>
                                </span>
                                <span class="shrink-0 text-sm" style="font-family: 'Scheherazade New', serif">{{ $s['nama'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </aside>

                {{-- Isi surah --}}
                <div class="w-full min-w-0">
                    <div class="overflow-hidden rounded-3xl border border-border bg-gradient-to-br from-primary/10 to-card p-6 text-center">
                        <p class="text-3xl font-bold" style="font-family: 'Scheherazade New', serif">{{ $detail['nama'] }}</p>
                        <h1 class="mt-2 text-xl font-bold tracking-tight">{{ $detail['namaLatin'] }}</h1>
                        <p class="text-sm text-muted-foreground">{{ $detail['arti'] }} · {{ $detail['jumlahAyat'] }} ayat · {{ $detail['tempatTurun'] }}</p>

                        @if (! empty($detail['audioFull']['05'] ?? $detail['audioFull']['01'] ?? null))
                            <audio controls class="mx-auto mt-4 w-full max-w-md" src="{{ $detail['audioFull']['05'] ?? $detail['audioFull']['01'] }}"></audio>
                        @endif
                    </div>

                    {{-- Kontrol tampilan --}}
                    <div class="mt-4 flex flex-wrap gap-2">
                        <button wire:click="$toggle('showTranslation')"
                                class="rounded-lg border px-3 py-2 text-xs font-medium transition-colors {{ $showTranslation ? 'border-primary bg-primary/10 text-primary' : 'border-border' }}">
                            Terjemah
                        </button>
                        <button wire:click="$toggle('showTafsir')"
                                class="rounded-lg border px-3 py-2 text-xs font-medium transition-colors {{ $showTafsir ? 'border-primary bg-primary/10 text-primary' : 'border-border' }}">
                            Tafsir
                        </button>
                    </div>

                    {{-- Ayat --}}
                    <div class="mt-4 space-y-3">
                        @foreach ($detail['ayat'] ?? [] as $ayat)
                            @php
                                $no = $ayat['nomorAyat'];
                                $isBookmarked = ($marks['bookmark'] ?? collect())->contains('ayah', $no);
                                $isHighlighted = ($marks['highlight'] ?? collect())->contains('ayah', $no);
                                $note = ($marks['note'] ?? collect())->firstWhere('ayah', $no);
                            @endphp

                            <div wire:key="a{{ $no }}"
                                 class="rounded-2xl border p-5 transition-colors {{ $isHighlighted ? 'border-amber-400/40 bg-amber-400/8' : 'border-border bg-card' }}">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="grid size-8 place-items-center rounded-full bg-primary/10 text-xs font-bold text-primary">{{ $no }}</span>

                                    <div class="flex gap-0.5">
                                        <button wire:click="toggleBookmark({{ $no }})" title="Bookmark"
                                                class="rounded-lg p-2 transition-colors {{ $isBookmarked ? 'text-primary' : 'text-muted-foreground hover:bg-accent' }}">
                                            <i data-lucide="bookmark" class="size-4"></i>
                                        </button>
                                        <button wire:click="highlight({{ $no }})" title="Highlight"
                                                class="rounded-lg p-2 transition-colors {{ $isHighlighted ? 'text-amber-500' : 'text-muted-foreground hover:bg-accent' }}">
                                            <i data-lucide="highlighter" class="size-4"></i>
                                        </button>
                                        <button wire:click="openNote({{ $no }})" title="Catatan"
                                                class="rounded-lg p-2 transition-colors {{ $note ? 'text-primary' : 'text-muted-foreground hover:bg-accent' }}">
                                            <i data-lucide="sticky-note" class="size-4"></i>
                                        </button>
                                        <button wire:click="markLastRead({{ $no }})" title="Tandai terakhir dibaca"
                                                class="rounded-lg p-2 text-muted-foreground transition-colors hover:bg-accent">
                                            <i data-lucide="book-marked" class="size-4"></i>
                                        </button>
                                    </div>
                                </div>

                                <p class="quran-arabic mt-4">{{ $ayat['teksArab'] }}</p>

                                @if ($showTranslation)
                                    <p class="mt-3 text-sm italic text-muted-foreground">{{ $ayat['teksLatin'] }}</p>
                                    <p class="mt-2 text-sm leading-relaxed">{{ $ayat['teksIndonesia'] }}</p>
                                @endif

                                @if ($showTafsir && ! empty($tafsir[$no]['teks']))
                                    <div class="mt-4 rounded-xl bg-muted/50 p-4">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Tafsir</p>
                                        <p class="mt-1.5 text-sm leading-relaxed text-muted-foreground">{{ $tafsir[$no]['teks'] }}</p>
                                    </div>
                                @endif

                                @if ($note?->note)
                                    <div class="mt-3 rounded-xl border border-primary/25 bg-primary/5 p-3">
                                        <p class="text-xs font-semibold text-primary">Catatan Anda</p>
                                        <p class="mt-1 text-sm">{{ $note->note }}</p>
                                    </div>
                                @endif

                                @if (! empty($ayat['audio']['05'] ?? null))
                                    <audio controls class="mt-3 w-full" src="{{ $ayat['audio']['05'] }}"></audio>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Navigasi surah --}}
                    <div class="mt-6 flex justify-between gap-3">
                        @if ($surah > 1)
                            <button wire:click="open({{ $surah - 1 }})"><x-ui.button variant="outline" icon="chevron-left">Surah Sebelumnya</x-ui.button></button>
                        @else <span></span> @endif
                        @if ($surah < 114)
                            <button wire:click="open({{ $surah + 1 }})"><x-ui.button variant="outline" iconEnd="chevron-right">Surah Berikutnya</x-ui.button></button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Modal catatan --}}
            @if ($noteAyah)
                <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-background/70 backdrop-blur-sm" wire:click="$set('noteAyah', null)"></div>
                    <div class="relative w-full max-w-md rounded-2xl border border-border bg-card p-6 shadow-2xl">
                        <h3 class="font-bold">Catatan untuk ayat {{ $noteAyah }}</h3>
                        <textarea wire:model="noteText" rows="5" placeholder="Tulis renungan atau catatan pribadi Anda…"
                                  class="mt-4 w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                        <div class="mt-4 flex justify-end gap-2">
                            <x-ui.button variant="outline" wire:click="$set('noteAyah', null)">Batal</x-ui.button>
                            <x-ui.button wire:click="saveNote" icon="save">Simpan</x-ui.button>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
