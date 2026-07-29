<div>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{-- Back Navigation --}}
        <div class="mb-6">
            <a href="{{ route('artikel') }}" wire:navigate class="inline-flex items-center gap-2 rounded-full bg-card border border-border px-4 py-1.5 text-xs font-bold text-muted-foreground hover:text-foreground hover:border-amber-500/40 transition-all shadow-sm">
                <i data-lucide="arrow-left" class="size-4 text-amber-500"></i>
                <span>Kembali ke Semua Artikel</span>
            </a>
        </div>

        <div class="grid gap-10 lg:grid-cols-[1fr_20rem]">
            <article class="min-w-0">
                {{-- Header & Meta --}}
                <div>
                    @if ($article->category)
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/15 px-3 py-1 text-xs font-bold text-amber-600 dark:text-amber-400 border border-amber-500/20 mb-3">
                            <i data-lucide="newspaper" class="size-3.5"></i> {{ $article->category->name }}
                        </span>
                    @endif

                    <h1 class="text-2xl sm:text-4xl font-extrabold leading-tight tracking-tight text-foreground font-jakarta">{{ $article->title }}</h1>

                    <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs sm:text-sm text-muted-foreground border-b border-border pb-5">
                        <span class="flex items-center gap-2 font-semibold text-foreground">
                            <x-ui.avatar :name="$article->author?->name ?? 'Redaksi'" size="xs" class="border border-amber-500/30" />
                            {{ $article->author?->name ?? 'Redaksi Masjid' }}
                        </span>
                        <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="size-4 text-amber-500"></i>{{ tanggal_id($article->published_at) }}</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="clock" class="size-4 text-amber-500"></i>{{ $article->reading_time }} menit baca</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="eye" class="size-4 text-amber-500"></i>{{ number_format($article->views) }}x dilihat</span>
                    </div>
                </div>

                {{-- Featured Cover Image --}}
                <div class="mt-6 overflow-hidden rounded-3xl border border-border shadow-lg max-h-[480px] bg-muted relative">
                    <img src="{{ img_url($article->cover, $article->slug) }}" alt="{{ $article->title }}" class="w-full h-full object-cover" />
                </div>

                {{-- Article Body Content --}}
                <div class="prose-masjid mt-8 text-base sm:text-lg leading-relaxed text-foreground/90 font-normal">
                    {!! $article->body !!}
                </div>

                {{-- Share Bar --}}
                <div class="mt-10 rounded-2xl border border-border bg-card p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="grid size-9 place-items-center rounded-xl bg-amber-500/15 text-amber-600 dark:text-amber-400">
                            <i data-lucide="share-2" class="size-4"></i>
                        </span>
                        <div>
                            <p class="text-xs font-bold text-foreground">Bagikan Artikel Ini</p>
                            <p class="text-[0.7rem] text-muted-foreground">Sebarkan kebaikan untuk jamaah lainnya</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto justify-end">
                        <a href="https://wa.me/?text={{ urlencode($article->title.' — '.url()->current()) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-3 py-2 text-xs font-bold text-emerald-600 hover:bg-emerald-500/20 transition-all">
                            <i data-lucide="message-circle" class="size-4"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-blue-500/30 bg-blue-500/10 px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-500/20 transition-all">
                            <i data-lucide="facebook" class="size-4"></i> Facebook
                        </a>
                        <button x-data @click="navigator.clipboard.writeText(location.href); $dispatch('toast', { message: 'Tautan disalin ke clipboard.', variant: 'success' })"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-amber-500/30 bg-amber-500/10 px-3 py-2 text-xs font-bold text-amber-600 hover:bg-amber-500/20 transition-all">
                            <i data-lucide="link" class="size-4"></i> Salin Link
                        </button>
                    </div>
                </div>

                {{-- Comment Section --}}
                <section class="mt-12 space-y-6">
                    <div class="flex items-center justify-between border-b border-border pb-3">
                        <h2 class="text-xl font-extrabold tracking-tight font-jakarta flex items-center gap-2">
                            <i data-lucide="messages-square" class="size-5 text-amber-500"></i>
                            <span>Komentar Jamaah ({{ $comments->count() }})</span>
                        </h2>
                    </div>

                    <form wire:submit="comment" class="rounded-3xl border border-border bg-card p-5 sm:p-6 shadow-sm space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Tulis Komentar</h3>
                        <div class="grid gap-4 sm:grid-cols-[14rem_1fr]">
                            <x-ui.input wire:model="commentName" label="Nama Lengkap" :error="$errors->first('commentName')" placeholder="Nama Anda" />
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-foreground">Komentar</label>
                                <textarea wire:model="commentBody" rows="3" placeholder="Tulis tanggapan atau opini Anda..."
                                          class="w-full rounded-xl border {{ $errors->has('commentBody') ? 'border-destructive' : 'border-input' }} bg-background px-3 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/50"></textarea>
                                @error('commentBody')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <x-ui.button type="submit" size="sm" icon="send" class="bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl px-5">Kirim Komentar</x-ui.button>
                        </div>
                    </form>

                    <div class="space-y-3.5 mt-4">
                        @forelse ($comments as $c)
                            <div class="flex gap-3.5 rounded-2xl border border-border/80 bg-card p-4 sm:p-5 shadow-xs">
                                <x-ui.avatar :name="$c->name" size="md" class="shrink-0 border border-amber-500/30" />
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="font-bold text-sm text-foreground">{{ $c->name }}</p>
                                        <span class="text-[0.7rem] text-muted-foreground font-medium">{{ $c->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-foreground/85 font-normal">{{ $c->body }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-8 text-sm text-muted-foreground bg-card rounded-2xl border border-dashed border-border">Belum ada komentar. Jadilah yang pertama memberikan inspirasi.</p>
                        @endforelse
                    </div>
                </section>
            </article>

            {{-- Sidebar --}}
            <aside class="space-y-6">
                @if ($related->isNotEmpty())
                    <div class="rounded-3xl border border-border bg-card p-6 shadow-sm lg:sticky lg:top-24">
                        <h2 class="font-bold text-base border-b border-border pb-3 flex items-center gap-2">
                            <i data-lucide="sparkles" class="size-4 text-amber-500"></i> Artikel Terkait
                        </h2>
                        <div class="mt-4 space-y-4">
                            @foreach ($related as $r)
                                <a href="{{ route('artikel.show', $r) }}" wire:navigate class="flex gap-3 group items-center">
                                    <div class="size-16 shrink-0 overflow-hidden rounded-xl bg-muted border border-border">
                                        <img src="{{ img_url($r->cover, $r->slug) }}" alt="{{ $r->title }}" class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="line-clamp-2 text-xs sm:text-sm font-bold text-foreground leading-snug group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ $r->title }}</p>
                                        <p class="mt-1 text-[0.7rem] text-muted-foreground font-medium flex items-center gap-1">
                                            <i data-lucide="clock" class="size-3 text-amber-500"></i>
                                            <span>{{ tanggal_id($r->published_at, false) }}</span>
                                        </p>
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
