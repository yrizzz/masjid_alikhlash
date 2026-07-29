<div>
    <x-page-hero title="Galeri" icon="images" subtitle="Dokumentasi kegiatan dan perkembangan masjid dari waktu ke waktu." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if ($categories->isNotEmpty())
            <div class="flex flex-wrap gap-1.5">
                <button wire:click="$set('kategori', '')"
                        class="rounded-full px-3.5 py-1.5 text-sm font-medium transition-colors {{ $kategori === '' ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:text-foreground' }}">Semua</button>
                @foreach ($categories as $c)
                    <button wire:click="$set('kategori', '{{ $c->slug }}')"
                            class="rounded-full px-3.5 py-1.5 text-sm font-medium transition-colors {{ $kategori === $c->slug ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:text-foreground' }}">{{ $c->name }}</button>
                @endforeach
            </div>
        @endif

        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($galleries as $g)
                <a href="{{ route('galeri.show', $g) }}" wire:navigate
                   class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-1 hover:shadow-lg">
                    <div class="relative aspect-[4/3] overflow-hidden bg-muted">
                        <img src="{{ img_url($g->cover ?: $g->photos->first()?->path, $g->slug) }}" alt="{{ $g->title }}"
                             class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        <span class="absolute end-3 top-3 rounded-full bg-background/90 px-2.5 py-1 text-xs font-semibold backdrop-blur">
                            {{ $g->photos_count }} foto
                        </span>
                    </div>
                    <div class="p-4">
                        @if ($g->category)<span class="text-xs font-medium text-primary">{{ $g->category->name }}</span>@endif
                        <h3 class="mt-0.5 font-semibold leading-snug">{{ $g->title }}</h3>
                        <p class="mt-1 text-xs text-muted-foreground">{{ $g->taken_at ? tanggal_id($g->taken_at, false) : '' }}</p>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 lg:col-span-3">
                    <x-empty-state icon="images" title="Belum ada album" message="Dokumentasi kegiatan akan tampil di sini." />
                </div>
            @endforelse
        </div>

        @if ($galleries->hasPages())
            <div class="mt-8">{{ $galleries->links() }}</div>
        @endif
    </div>
</div>
