<div>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Back Navigation --}}
        <div class="mb-6">
            <a href="{{ route('kajian') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full bg-card border border-border px-4 py-1.5 text-xs font-bold text-muted-foreground hover:text-foreground hover:border-amber-500/40 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="size-4 text-amber-500"></i>
                <span>Kembali ke Jadwal Kajian</span>
            </a>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
            <article class="min-w-0">
                {{-- Media utama --}}
                @if ($kajian->media_type === 'video' && $kajian->youtube_id)
                    <div class="overflow-hidden rounded-3xl border border-border bg-stone-950 shadow-xl">
                        <iframe class="aspect-video w-full" src="https://www.youtube.com/embed/{{ $kajian->youtube_id }}"
                                title="{{ $kajian->title }}" allowfullscreen loading="lazy" style="border:0"></iframe>
                    </div>
                @else
                    <div class="overflow-hidden rounded-3xl border border-border shadow-xl max-h-[440px] bg-stone-950">
                        <img src="{{ img_url($kajian->poster, $kajian->slug) }}" alt="{{ $kajian->title }}" class="w-full h-full object-cover" />
                    </div>
                @endif

                <div class="mt-7 space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if ($kajian->category)
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                    <i data-lucide="book-open" class="size-3.5"></i> {{ $kajian->category->name }}
                                </span>
                            @endif
                            @if ($kajian->is_today)
                                <span class="rounded-full bg-amber-500 px-3 py-1 text-xs font-extrabold text-stone-950">HARI INI</span>
                            @endif
                        </div>

                        <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight text-foreground font-jakarta">{{ $kajian->title }}</h1>

                        <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs sm:text-sm text-muted-foreground border-b border-border pb-5 font-medium">
                            <span class="flex items-center gap-1.5 text-foreground font-bold"><i data-lucide="user-round" class="size-4 text-amber-500"></i>{{ $kajian->ustadz }}</span>
                            @if ($kajian->start_at)
                                <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="size-4 text-amber-500"></i>{{ tanggal_id($kajian->start_at) }}</span>
                                <span class="flex items-center gap-1.5"><i data-lucide="clock" class="size-4 text-amber-500"></i>{{ $kajian->start_at->format('H:i') }} WIB</span>
                            @endif
                            <span class="flex items-center gap-1.5"><i data-lucide="map-pin" class="size-4 text-amber-500"></i>{{ $kajian->location }}</span>
                            <span class="flex items-center gap-1.5"><i data-lucide="eye" class="size-4 text-amber-500"></i>{{ number_format($kajian->views, 0, ',', '.') }}x dilihat</span>
                        </div>
                    </div>

                    {{-- Actions Bar --}}
                    <div class="flex flex-wrap items-center gap-2.5 bg-card border border-border p-4 rounded-2xl shadow-xs">
                        <button wire:click="toggleBookmark">
                            <x-ui.button size="sm" :variant="$bookmarked ? 'default' : 'outline'" icon="bookmark" class="{{ $bookmarked ? 'bg-amber-600 hover:bg-amber-700 text-white font-bold' : '' }}">
                                {{ $bookmarked ? 'Tersimpan' : 'Simpan Kajian' }}
                            </x-ui.button>
                        </button>
                        @if ($kajian->media_type === 'audio' && $kajian->media_url)
                            <a href="{{ $kajian->media_url }}" target="_blank">
                                <x-ui.button size="sm" variant="outline" icon="headphones">Dengar Audio</x-ui.button>
                            </a>
                        @endif
                        @if (in_array($kajian->media_type, ['pdf', 'slide'], true) && ($kajian->media_url || $kajian->attachment))
                            <a href="{{ $kajian->media_url ?: img_url($kajian->attachment) }}" target="_blank">
                                <x-ui.button size="sm" variant="outline" icon="file-down">Unduh Materi PDF</x-ui.button>
                            </a>
                        @endif
                        <button x-data @click="navigator.share ? navigator.share({ title: @js($kajian->title), url: location.href }) : (navigator.clipboard.writeText(location.href), $dispatch('toast', { message: 'Tautan kajian disalin.', variant: 'success' }))">
                            <x-ui.button size="sm" variant="outline" icon="share-2">Bagikan Tautan</x-ui.button>
                        </button>
                    </div>

                    @if ($kajian->recurrence)
                        <div class="rounded-2xl border border-amber-500/30 bg-amber-500/10 p-4 flex items-center gap-3">
                            <i data-lucide="repeat" class="size-5 text-amber-500 shrink-0"></i>
                            <div>
                                <p class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Jadwal Rutin</p>
                                <p class="text-xs sm:text-sm font-medium text-foreground mt-0.5">{{ $kajian->recurrence }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Audio player --}}
                    @if ($kajian->media_type === 'audio' && $kajian->media_url)
                        <div class="rounded-2xl border border-border bg-card p-4">
                            <p class="text-xs font-bold text-foreground mb-2 flex items-center gap-2">
                                <i data-lucide="headphones" class="size-4 text-amber-500"></i> Player Rekaman Audio
                            </p>
                            <audio controls class="w-full" src="{{ $kajian->media_url }}"></audio>
                        </div>
                    @endif

                    {{-- Deskripsi Ringkasan --}}
                    <div class="space-y-3 pt-2">
                        <h2 class="text-lg font-bold text-foreground font-jakarta flex items-center gap-2 border-b border-border pb-2">
                            <i data-lucide="file-text" class="size-4 text-amber-500"></i> Deskripsi & Pembahasan Kajian
                        </h2>
                        <div class="prose-masjid text-sm sm:text-base leading-relaxed text-foreground/90 font-normal">
                            {!! $kajian->description ?: '<p>'.e($kajian->excerpt).'</p>' !!}
                        </div>
                    </div>
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                {{-- Pendaftaran Kehadiran --}}
                @if ($kajian->open_registration)
                    <div class="rounded-3xl border border-amber-500/40 bg-card p-6 shadow-md space-y-4">
                        @if ($registration)
                            <div class="text-center space-y-3">
                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/20 px-3 py-1 text-xs font-bold text-amber-600 dark:text-amber-400 border border-amber-500/30">
                                    <i data-lucide="check-circle-2" class="size-4"></i> Anda Terdaftar
                                </span>
                                <p class="text-xs text-muted-foreground">Tunjukkan kode QR ini ke panitia di lokasi masjid untuk check-in.</p>
                                <div class="rounded-2xl bg-white p-4 text-center border border-border shadow-inner max-w-[180px] mx-auto">
                                    {!! qr_svg($registration->code, 150) !!}
                                    <p class="mt-2 font-mono text-xs font-bold tracking-wider text-stone-900">{{ $registration->code }}</p>
                                </div>
                                @if ($registration->checked_in_at)
                                    <p class="text-xs font-bold text-amber-600">Sudah Check-In {{ $registration->checked_in_at->format('d/m H:i') }}</p>
                                @endif
                            </div>
                        @else
                            <div>
                                <h2 class="font-bold text-base text-foreground flex items-center gap-2 border-b border-border pb-2">
                                    <i data-lucide="ticket" class="size-4 text-amber-500"></i> Pendaftaran Kehadiran
                                </h2>
                                <p class="mt-2 text-xs text-muted-foreground leading-relaxed">
                                    @if ($seats !== null)
                                        Sisa <strong class="text-amber-600 font-bold">{{ $seats }} kursi</strong> dari {{ $kajian->quota }} kuota.
                                    @else
                                        Dapatkan Tiket QR Code gratis untuk presisi check-in di masjid.
                                    @endif
                                </p>
                            </div>

                            <form wire:submit="register" class="space-y-3">
                                <x-ui.input wire:model="name" label="Nama Lengkap" name="name" :error="$errors->first('name')" placeholder="Nama Anda" />
                                <x-ui.input wire:model="phone" label="Nomor WhatsApp" name="phone" :error="$errors->first('phone')" placeholder="08123456789" />
                                <x-ui.button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-bold rounded-xl text-xs py-2.5" icon="user-plus">Daftar Sekarang</x-ui.button>
                            </form>
                        @endif
                    </div>
                @endif

                {{-- Kajian Terkait --}}
                <div class="rounded-3xl border border-border bg-card p-6 shadow-sm">
                    <h2 class="font-bold text-sm border-b border-border pb-3 flex items-center gap-2">
                        <i data-lucide="sparkles" class="size-4 text-amber-500"></i> Kajian Terkait
                    </h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($related as $r)
                            <a href="{{ route('kajian.show', $r) }}" wire:navigate class="flex gap-3 group items-center p-2 rounded-xl hover:bg-muted/50 transition-colors">
                                <div class="size-14 shrink-0 overflow-hidden rounded-xl border border-border bg-muted">
                                    <img src="{{ img_url($r->poster, $r->slug) }}" alt="{{ $r->title }}" class="size-full object-cover group-hover:scale-105 transition-transform" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="line-clamp-2 text-xs font-bold text-foreground leading-snug group-hover:text-amber-600 transition-colors">{{ $r->title }}</p>
                                    <p class="mt-0.5 truncate text-[0.7rem] text-muted-foreground">{{ $r->ustadz }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-muted-foreground py-4 text-center">Belum ada kajian terkait.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
