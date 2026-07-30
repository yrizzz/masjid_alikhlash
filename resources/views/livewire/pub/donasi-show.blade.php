<div>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Back Navigation --}}
        <div class="mb-6">
            <a href="{{ route('donasi') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full bg-card border border-border px-4 py-1.5 text-xs font-bold text-muted-foreground hover:text-foreground hover:border-amber-500/40 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="size-4 text-amber-500"></i>
                <span>Kembali ke Semua Program Donasi</span>
            </a>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
            {{-- Main Campaign Article --}}
            <article class="min-w-0">
                <div class="overflow-hidden rounded-3xl border border-border shadow-lg bg-stone-950 max-h-[460px]">
                    <img src="{{ img_url($campaign->cover, $campaign->slug) }}" alt="{{ $campaign->title }}" class="aspect-[16/9] w-full object-cover" />
                </div>

                <div class="mt-6 space-y-4">
                    <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight text-foreground font-jakarta">{{ $campaign->title }}</h1>
                    <p class="text-sm sm:text-base leading-relaxed text-muted-foreground font-medium">{{ $campaign->excerpt }}</p>

                    <div class="prose-masjid border-t border-border pt-6 text-sm sm:text-base leading-relaxed text-foreground/90 font-normal">
                        {!! $campaign->description ?: '<p class="text-muted-foreground">Cerita lengkap campaign belum diisi.</p>' !!}
                    </div>
                </div>

                {{-- Kabar Terbaru / Campaign Updates --}}
                @if ($updates->isNotEmpty())
                    <section class="mt-10 space-y-4">
                        <h2 class="text-xl font-extrabold tracking-tight font-jakarta flex items-center gap-2 border-b border-border pb-3">
                            <i data-lucide="bell" class="size-5 text-amber-500"></i> Kabar Terbaru & Pencairan
                        </h2>
                        <div class="space-y-4">
                            @foreach ($updates as $u)
                                <div class="rounded-3xl border border-border bg-card p-5 sm:p-6 shadow-sm space-y-2">
                                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">{{ tanggal_id($u->created_at) }}</span>
                                    <h3 class="font-extrabold text-base text-foreground">{{ $u->title }}</h3>
                                    <p class="text-xs sm:text-sm leading-relaxed text-muted-foreground">{{ $u->body }}</p>
                                    @if ($u->image)
                                        <img src="{{ img_url($u->image) }}" alt="" class="mt-3 rounded-2xl max-h-80 w-full object-cover border border-border" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                {{-- Donor List --}}
                <section class="mt-10 space-y-4">
                    <div class="flex items-center justify-between border-b border-border pb-3">
                        <h2 class="text-xl font-extrabold tracking-tight font-jakarta flex items-center gap-2">
                            <i data-lucide="heart-handshake" class="size-5 text-amber-500"></i> Donatur Terdaftar ({{ $campaign->donor_count }})
                        </h2>
                    </div>

                    <div class="divide-y divide-border/60 rounded-3xl border border-border bg-card shadow-sm">
                        @forelse ($donations as $d)
                            <div class="flex items-start gap-3.5 p-4 sm:p-5">
                                <x-ui.avatar :name="$d->display_name" size="md" class="shrink-0 border border-amber-500/30" />
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                                        <p class="font-bold text-sm text-foreground">{{ $d->display_name }}</p>
                                        <span class="font-outfit text-sm font-extrabold text-amber-600 dark:text-amber-400">{{ rupiah($d->amount) }}</span>
                                    </div>
                                    @if ($d->message)
                                        <p class="mt-1 text-xs italic text-muted-foreground font-medium bg-muted/50 p-2.5 rounded-xl border border-border/40">“{{ $d->message }}”</p>
                                    @endif
                                    <p class="mt-1.5 text-[0.7rem] text-muted-foreground">{{ $d->paid_at?->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-sm text-muted-foreground">Belum ada donasi masuk. Jadilah donatur pertama untuk campaign ini.</p>
                        @endforelse

                        @if ($hasMoreDonors)
                            <div class="p-4 text-center border-t border-border/60">
                                <button wire:click="loadMoreDonors" wire:loading.attr="disabled"
                                        class="inline-flex items-center gap-2 rounded-xl border border-amber-500/30 bg-amber-500/10 px-5 py-2.5 text-xs font-bold text-amber-600 dark:text-amber-400 hover:bg-amber-500/20 transition-all cursor-pointer">
                                    <span wire:loading.remove wire:target="loadMoreDonors" class="inline-flex items-center gap-1.5">
                                        <i data-lucide="chevron-down" class="size-4"></i> Muat Lebih Banyak Donatur ({{ $donations->count() }}/{{ $totalDonorsCount }})
                                    </span>
                                    <span wire:loading wire:target="loadMoreDonors">Memuat data donatur...</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </section>
            </article>

            {{-- Donation Payment Sidebar --}}
            <aside class="lg:sticky lg:top-24 lg:self-start space-y-6">
                <div class="rounded-3xl border border-amber-500/30 bg-card p-6 shadow-md space-y-5">
                    <div>
                        <p class="font-outfit text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ rupiah($campaign->collected) }}</p>
                        <p class="text-xs font-medium text-muted-foreground mt-0.5">terkumpul dari target <strong class="text-foreground font-semibold">{{ rupiah($campaign->target) }}</strong></p>
                    </div>

                    <div class="fund-bar"><span style="width: {{ $campaign->progress }}%"></span></div>

                    <div class="grid grid-cols-3 gap-2 text-center">
                        @foreach ([
                            [$campaign->progress.'%', 'Tercapai'],
                            [$campaign->donor_count, 'Donatur'],
                            [$campaign->days_left !== null ? $campaign->days_left : '∞', 'Hari lagi'],
                        ] as [$v, $l])
                            <div class="rounded-2xl bg-muted/60 py-2.5 border border-border/40">
                                <p class="font-outfit text-sm font-extrabold text-foreground">{{ $v }}</p>
                                <p class="text-[0.65rem] text-muted-foreground font-medium uppercase tracking-wider mt-0.5">{{ $l }}</p>
                            </div>
                        @endforeach
                    </div>

                    @if ($created)
                        {{-- Payment Confirmation Result --}}
                        <div class="rounded-2xl border border-amber-500/40 bg-amber-500/10 p-5 space-y-3">
                            <p class="flex items-center gap-2 font-bold text-amber-600 dark:text-amber-400 text-sm">
                                <i data-lucide="check-circle-2" class="size-4"></i> Donasi Anda Tercatat
                            </p>
                            <p class="text-xs text-muted-foreground">Kode Pembayaran: <span class="font-mono font-bold text-foreground">{{ $created->code }}</span></p>
                            <p class="font-outfit text-2xl font-extrabold text-foreground">{{ rupiah($created->amount) }}</p>

                            @php $ch = $created->channel; @endphp
                            @if ($ch)
                                <div class="rounded-xl bg-card p-3.5 border border-border">
                                    <p class="text-xs font-bold text-foreground">{{ $ch->name }}</p>
                                    @if ($ch->type === 'qris' && $ch->qr_image)
                                        <img src="{{ img_url($ch->qr_image) }}" alt="QRIS" class="mx-auto mt-2 max-w-[12rem] rounded-xl border border-border shadow-xs" />
                                    @elseif ($ch->account_number)
                                        <p class="mt-1 font-mono text-base font-extrabold text-amber-600 dark:text-amber-400 tracking-wider">{{ $ch->account_number }}</p>
                                        <p class="text-[0.7rem] text-muted-foreground">a.n. {{ $ch->account_name }}</p>
                                    @endif
                                    @if ($ch->instruction)
                                        <p class="mt-2 text-[0.7rem] leading-relaxed text-muted-foreground">{{ $ch->instruction }}</p>
                                    @endif
                                </div>
                            @endif

                            @if (setting('wa_number'))
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', setting('wa_number')) }}?text={{ urlencode('Assalamu\'alaikum, saya sudah mentransfer donasi dengan kode '.$created->code) }}"
                                   target="_blank" class="block">
                                    <x-ui.button class="w-full justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs py-2.5" icon="message-circle">
                                        Konfirmasi via WhatsApp
                                    </x-ui.button>
                                </a>
                            @endif

                            <button wire:click="$set('created', null)" class="w-full text-xs text-muted-foreground hover:text-foreground font-medium underline">Donasi lagi</button>
                        </div>
                    @else
                        {{-- Donation Input Form --}}
                        <form wire:submit="submit" class="space-y-4 pt-1">
                            <div>
                                <label class="block text-xs font-bold text-foreground uppercase tracking-wider mb-2">Pilih Nominal Donasi</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach ($presets as $p)
                                        <button type="button" wire:click="pick({{ $p }})"
                                                class="rounded-xl border py-2 text-xs font-extrabold font-outfit transition-all {{ (int) $amount === $p ? 'border-amber-500 bg-amber-500/20 text-amber-600 dark:text-amber-300' : 'border-border bg-muted/40 hover:bg-muted text-foreground' }}">
                                            {{ rupiah_short($p) }}
                                        </button>
                                    @endforeach
                                </div>
                                <div class="relative mt-2.5">
                                    <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-xs font-bold text-muted-foreground">Rp</span>
                                    <input type="number" wire:model="amount" placeholder="Nominal bebas"
                                           class="h-10 w-full rounded-xl border {{ $errors->has('amount') ? 'border-destructive' : 'border-input' }} bg-background ps-9 pe-3 text-xs font-bold focus:outline-none focus:ring-2 focus:ring-amber-500/50" />
                                </div>
                                @error('amount')<p class="mt-1 text-xs text-destructive">{{ $message }}</p>@enderror
                            </div>

                            <x-ui.input wire:model="name" label="Nama Donatur" :error="$errors->first('name')" placeholder="Nama Anda / Hamba Allah" />
                            <x-ui.input wire:model="phone" label="Nomor WhatsApp" :error="$errors->first('phone')" placeholder="08123456789" />

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-foreground">Metode Pembayaran</label>
                                <select wire:model="channelId" class="h-10 w-full rounded-xl border border-input bg-background px-3 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/50">
                                    @foreach ($channels as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ ucfirst($c->type) }})</option>
                                    @endforeach
                                </select>
                                @error('channelId')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-foreground">Pesan / Doa <span class="text-muted-foreground font-normal">(opsional)</span></label>
                                <textarea wire:model="message" rows="2" placeholder="Tuliskan doa atau pesan Anda..." class="w-full rounded-xl border border-input bg-background px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/50"></textarea>
                            </div>

                            <label class="flex items-center gap-2.5 text-xs text-muted-foreground cursor-pointer font-medium">
                                <input type="checkbox" wire:model="anonymous" class="size-4 rounded border-input text-amber-600 focus:ring-amber-500" />
                                Sembunyikan nama saya (Hamba Allah)
                            </label>

                            <x-ui.button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800 text-white font-bold rounded-xl py-3 text-sm shadow-md" size="lg" icon="hand-heart">
                                <span wire:loading.remove wire:target="submit">Infaq / Donasi Sekarang</span>
                                <span wire:loading wire:target="submit">Memproses…</span>
                            </x-ui.button>
                        </form>
                    @endif
                </div>

                @if ($others->isNotEmpty())
                    <div class="rounded-3xl border border-border bg-card p-6 shadow-sm">
                        <h2 class="font-bold text-sm border-b border-border pb-3 flex items-center gap-2">
                            <i data-lucide="hand-heart" class="size-4 text-amber-500"></i> Program Campaign Lainnya
                        </h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($others as $o)
                                <a href="{{ route('donasi.show', $o) }}" wire:navigate class="flex gap-3 group items-center p-2 rounded-xl hover:bg-muted/50 transition-colors">
                                    <div class="size-14 shrink-0 overflow-hidden rounded-xl border border-border bg-muted">
                                        <img src="{{ img_url($o->cover, $o->slug) }}" alt="{{ $o->title }}" class="size-full object-cover group-hover:scale-105 transition-transform" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="line-clamp-2 text-xs font-bold text-foreground leading-snug group-hover:text-amber-600 transition-colors">{{ $o->title }}</p>
                                        <p class="mt-1 font-outfit text-xs font-extrabold text-amber-600 dark:text-amber-400">{{ $o->progress }}% terkumpul</p>
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
