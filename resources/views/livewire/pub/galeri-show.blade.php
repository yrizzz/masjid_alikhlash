<div x-data="{ open: false, index: 0, photos: {{ $gallery->photos->map(fn ($p) => ['src' => img_url($p->path, $p->id), 'caption' => $p->caption])->toJson() }} }">
    <x-page-hero :title="$gallery->title" icon="images" compact
                 :subtitle="$gallery->description ?: ($gallery->taken_at ? tanggal_id($gallery->taken_at) : null)" />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <a href="{{ route('galeri') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-muted-foreground hover:text-foreground">
            <i data-lucide="arrow-left" class="size-4"></i>Semua album
        </a>

        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @forelse ($gallery->photos as $i => $photo)
                <button @click="index = {{ $i }}; open = true"
                        class="group relative aspect-square overflow-hidden rounded-xl bg-muted">
                    <img src="{{ img_url($photo->path, $photo->id) }}" alt="{{ $photo->caption }}"
                         class="size-full object-cover transition-transform duration-500 group-hover:scale-110" />
                    @if ($photo->caption)
                        <span class="absolute inset-x-0 bottom-0 truncate bg-gradient-to-t from-black/70 to-transparent p-2 text-start text-xs text-white opacity-0 transition-opacity group-hover:opacity-100">
                            {{ $photo->caption }}
                        </span>
                    @endif
                </button>
            @empty
                <div class="col-span-full">
                    <x-empty-state icon="image" title="Album masih kosong" />
                </div>
            @endforelse
        </div>

        @if ($others->isNotEmpty())
            <h2 class="mt-12 text-lg font-bold">Album Lainnya</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($others as $o)
                    <a href="{{ route('galeri.show', $o) }}" wire:navigate class="group overflow-hidden rounded-2xl border border-border bg-card">
                        <div class="aspect-[4/3] overflow-hidden bg-muted">
                            <img src="{{ img_url($o->cover, $o->slug) }}" alt="" class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        </div>
                        <p class="truncate p-3 text-sm font-medium">{{ $o->title }}</p>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Lightbox --}}
    <div x-show="open" x-cloak @keydown.escape.window="open = false"
         @keydown.arrow-right.window="index = (index + 1) % photos.length"
         @keydown.arrow-left.window="index = (index - 1 + photos.length) % photos.length"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90 p-4">
        <button @click="open = false" class="absolute end-4 top-4 rounded-lg p-2 text-white/80 hover:bg-white/10"><i data-lucide="x" class="size-6"></i></button>
        <button @click="index = (index - 1 + photos.length) % photos.length" class="absolute start-4 rounded-full bg-white/10 p-3 text-white hover:bg-white/20"><i data-lucide="chevron-left" class="size-6"></i></button>
        <button @click="index = (index + 1) % photos.length" class="absolute end-4 rounded-full bg-white/10 p-3 text-white hover:bg-white/20"><i data-lucide="chevron-right" class="size-6"></i></button>

        <div class="max-h-full max-w-5xl text-center">
            <img :src="photos[index]?.src" class="mx-auto max-h-[80vh] rounded-xl object-contain" alt="" />
            <p class="mt-3 text-sm text-white/70" x-text="photos[index]?.caption"></p>
            <p class="mt-1 text-xs text-white/40" x-text="`${index + 1} / ${photos.length}`"></p>
        </div>
    </div>
</div>
