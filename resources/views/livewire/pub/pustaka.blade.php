<div>
    <x-page-hero title="E-Library" icon="library"
                 subtitle="Kitab, materi kajian, slide, dan rekaman yang bisa diunduh jamaah." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
            <div class="relative w-full sm:flex-1">
                <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground"></i>
                <input wire:model.live.debounce.400ms="search" type="search" placeholder="Cari judul atau penulis…"
                       class="h-11 w-full rounded-xl border border-input bg-background ps-10 pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
            </div>
            <select wire:model.live="jenis" class="h-11 w-full sm:w-auto rounded-xl border border-input bg-background px-3 text-sm">
                <option value="">Semua jenis</option>
                @foreach ($types as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 w-full min-w-0">
            @forelse ($ebooks as $e)
                <div class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-1 hover:shadow-lg">
                    <div class="relative aspect-[3/4] overflow-hidden bg-muted">
                        <img src="{{ img_url($e->cover, $e->slug) }}" alt="{{ $e->title }}"
                             class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <span class="absolute start-3 top-3 rounded-full bg-background/90 px-2.5 py-1 text-[0.7rem] font-bold uppercase backdrop-blur">{{ $e->type }}</span>
                    </div>
                    <div class="p-4">
                        <h3 class="line-clamp-2 font-semibold leading-snug">{{ $e->title }}</h3>
                        @if ($e->author)<p class="mt-1 truncate text-xs text-muted-foreground">{{ $e->author }}</p>@endif
                        <div class="mt-3 flex items-center justify-between text-xs text-muted-foreground">
                            <span>{{ $e->pages ? $e->pages.' hlm' : ($e->category?->name ?? '') }}</span>
                            <span class="flex items-center gap-1"><i data-lucide="download" class="size-3"></i>{{ $e->downloads }}</span>
                        </div>
                        @if ($e->file || $e->external_url)
                            <a href="{{ $e->external_url ?: img_url($e->file) }}" target="_blank" class="mt-3 block">
                                <x-ui.button size="sm" variant="outline" class="w-full" icon="book-open">Buka</x-ui.button>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-4">
                    <x-empty-state icon="library" title="Pustaka masih kosong" message="Materi akan ditambahkan pengurus secara berkala." />
                </div>
            @endforelse
        </div>

        @if ($ebooks->hasPages())
            <div class="mt-8">{{ $ebooks->links() }}</div>
        @endif
    </div>
</div>
