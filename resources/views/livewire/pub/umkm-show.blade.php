<div>
    {{-- Hero Cover Header --}}
    <div class="relative h-48 sm:h-72 w-full overflow-hidden bg-stone-950">
        <img src="{{ img_url($business->cover, $business->slug) }}" alt="{{ $business->name }}" class="size-full object-cover opacity-60" />
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/60 to-transparent"></div>
        <div class="absolute inset-x-0 top-0 z-10">
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 sm:pt-6 lg:px-8">
                <a href="{{ route('umkm') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full bg-stone-950/70 border border-white/20 px-3.5 py-1.5 text-xs font-bold text-white backdrop-blur hover:bg-stone-900 transition-colors">
                    <i data-lucide="arrow-left" class="size-4 text-amber-400"></i>
                    <span>Kembali ke Katalog UMKM</span>
                </a>
            </div>
        </div>
    </div>

    <div class="mx-auto -mt-20 sm:-mt-24 max-w-7xl px-4 pb-16 sm:px-6 lg:px-8 relative z-10">
        <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
            <div class="space-y-8 min-w-0">
                {{-- Store Main Profile Card --}}
                <div class="rounded-3xl border border-border bg-card p-5 sm:p-7 shadow-lg">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5">
                        <div class="size-20 sm:size-24 shrink-0 overflow-hidden rounded-2xl border-2 border-amber-500/30 bg-background shadow-md">
                            <img src="{{ img_url($business->logo, $business->slug.'logo') }}" alt="{{ $business->name }}" class="size-full object-cover" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($business->category)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/15 px-3 py-0.5 text-xs font-bold text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                        <i data-lucide="store" class="size-3"></i> {{ $business->category->name }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 rounded-full bg-muted px-2.5 py-0.5 text-xs font-medium text-muted-foreground">
                                    <i data-lucide="eye" class="size-3"></i> {{ number_format($business->views) }}x dilihat
                                </span>
                            </div>
                            <h1 class="mt-2 text-2xl sm:text-3xl font-extrabold tracking-tight text-foreground font-jakarta">{{ $business->name }}</h1>
                            <p class="mt-1 text-xs sm:text-sm text-muted-foreground flex items-center gap-1.5 font-medium">
                                <i data-lucide="user-check" class="size-4 text-amber-500 shrink-0"></i>
                                <span>Pemilik: <strong class="text-foreground">{{ $business->owner }}</strong> (Jamaah Masjid Al-Ikhlash)</span>
                            </p>
                        </div>
                    </div>

                    @if ($business->description)
                        <div class="mt-6 border-t border-border/60 pt-5">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground mb-2">Tentang Usaha</h3>
                            <p class="text-sm leading-relaxed text-foreground/90 whitespace-pre-line font-normal">{{ $business->description }}</p>
                        </div>
                    @endif
                </div>

                {{-- Catalog Products --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-border pb-3">
                        <h2 class="text-lg sm:text-xl font-bold tracking-tight font-jakarta flex items-center gap-2">
                            <i data-lucide="shopping-bag" class="size-5 text-amber-500"></i>
                            <span>Katalog Produk & Layanan</span>
                        </h2>
                        <span class="text-xs font-medium text-muted-foreground">{{ $business->products->count() }} Produk Tersedia</span>
                    </div>

                    <div class="grid gap-4 sm:gap-5 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
                        @forelse ($business->products as $p)
                            <div class="group overflow-hidden rounded-2xl border border-border bg-card shadow-sm hover:border-amber-500/50 card-transition flex flex-col justify-between">
                                <div>
                                    <div class="aspect-square overflow-hidden bg-muted relative">
                                        <img src="{{ img_url($p->photo, 'p'.$p->id) }}" alt="{{ $p->name }}" class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                        <span class="absolute top-2.5 end-2.5 rounded-full bg-stone-950/80 backdrop-blur px-2.5 py-1 text-xs font-extrabold text-amber-300 border border-white/10 font-outfit">
                                            {{ rupiah($p->price) }}<span class="text-[0.65rem] font-normal text-amber-200/80">{{ $p->unit ? '/'.$p->unit : '' }}</span>
                                        </span>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-bold text-sm sm:text-base text-foreground leading-snug group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $p->name }}</h3>
                                        @if ($p->description)
                                            <p class="mt-1.5 line-clamp-2 text-xs text-muted-foreground leading-relaxed">{{ $p->description }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4 pt-0">
                                    @if ($business->wa_link)
                                        <a href="{{ $business->wa_link }}?text={{ urlencode('Assalamu\'alaikum, saya berminat membeli '.$p->name.' ('.$business->name.')') }}" target="_blank" class="block w-full">
                                            <x-ui.button size="sm" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-bold rounded-xl shadow" icon="message-circle">
                                                Pesan via WhatsApp
                                            </x-ui.button>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="sm:col-span-2 lg:col-span-3">
                                <x-empty-state icon="package" title="Belum ada produk" message="Pemilik usaha belum menambahkan daftar produk." />
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                {{-- Contact Card --}}
                <div class="rounded-3xl border border-border bg-card p-6 shadow-sm space-y-4">
                    <h2 class="font-bold text-base border-b border-border pb-3 flex items-center gap-2">
                        <i data-lucide="phone-call" class="size-4 text-amber-500"></i> Kontak Pengelola
                    </h2>
                    <div class="space-y-2.5">
                        @if ($business->wa_link)
                            <a href="{{ $business->wa_link }}" target="_blank" class="block">
                                <x-ui.button class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs py-2.5" icon="message-circle">
                                    Chat WhatsApp
                                </x-ui.button>
                            </a>
                        @endif
                        @if ($business->phone)
                            <a href="tel:{{ $business->phone }}" class="block">
                                <x-ui.button class="w-full justify-center rounded-xl text-xs py-2.5" variant="outline" icon="phone">
                                    {{ $business->phone }}
                                </x-ui.button>
                            </a>
                        @endif
                        @if ($business->instagram)
                            <a href="{{ $business->instagram }}" target="_blank" class="block">
                                <x-ui.button class="w-full justify-center rounded-xl text-xs py-2.5" variant="outline" icon="instagram">
                                    Instagram {{ '@'.basename(rtrim($business->instagram, '/')) }}
                                </x-ui.button>
                            </a>
                        @endif
                    </div>

                    @if ($business->address)
                        <div class="border-t border-border pt-4">
                            <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1">Alamat Lapak</p>
                            <p class="flex items-start gap-2 text-xs leading-relaxed text-foreground font-medium">
                                <i data-lucide="map-pin" class="mt-0.5 size-4 shrink-0 text-amber-500"></i>{{ $business->address }}
                            </p>
                        </div>
                    @endif
                </div>

                @if ($business->lat && $business->lng)
                    <div class="overflow-hidden rounded-3xl border border-border shadow-sm">
                        <iframe class="h-48 w-full" style="border:0" loading="lazy" title="Lokasi usaha"
                                src="https://www.openstreetmap.org/export/embed.html?bbox={{ $business->lng - 0.003 }},{{ $business->lat - 0.002 }},{{ $business->lng + 0.003 }},{{ $business->lat + 0.002 }}&layer=mapnik&marker={{ $business->lat }},{{ $business->lng }}"></iframe>
                    </div>
                @endif

                @if ($others->isNotEmpty())
                    <div class="rounded-3xl border border-border bg-card p-6 shadow-sm">
                        <h2 class="font-bold text-sm border-b border-border pb-3 flex items-center gap-2">
                            <i data-lucide="store" class="size-4 text-amber-500"></i> Usaha Jamaah Lainnya
                        </h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($others as $o)
                                <a href="{{ route('umkm.show', $o) }}" wire:navigate class="flex items-center gap-3 group p-2 rounded-xl hover:bg-muted/50 transition-colors">
                                    <div class="size-11 shrink-0 overflow-hidden rounded-xl border border-border bg-muted">
                                        <img src="{{ img_url($o->logo, $o->slug) }}" alt="{{ $o->name }}" class="size-full object-cover" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-bold text-foreground group-hover:text-amber-600 transition-colors">{{ $o->name }}</p>
                                        <p class="truncate text-[0.7rem] text-muted-foreground mt-0.5">{{ $o->owner }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>
