<div>
    <x-page-hero title="Marketplace UMKM Jamaah" icon="store"
                 subtitle="{{ $total }} usaha milik jamaah dan warga sekitar masjid. Belanja dari tetangga sendiri." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2.5 sm:flex-row sm:items-center">
            <div class="relative w-full sm:flex-1">
                <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground"></i>
                <input wire:model.live.debounce.400ms="search" type="search" placeholder="Cari usaha atau produk…"
                       class="h-11 w-full rounded-xl border border-input bg-background ps-10 pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
            </div>
            <select wire:model.live="kategori" class="h-11 w-full sm:w-auto rounded-xl border border-input bg-background px-3 text-sm">
                <option value="">Semua kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->slug }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 w-full min-w-0">
            @forelse ($businesses as $b)
                <a href="{{ route('umkm.show', $b) }}" wire:navigate
                   class="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:-translate-y-1 hover:shadow-lg">
                    <div class="relative aspect-[16/9] overflow-hidden bg-muted">
                        <img src="{{ img_url($b->cover, $b->slug) }}" alt="{{ $b->name }}"
                             class="size-full object-cover transition-transform duration-500 group-hover:scale-105" />
                        @if ($b->is_featured)
                            <span class="absolute start-3 top-3 rounded-full bg-amber-500 px-2.5 py-1 text-xs font-bold text-white">PILIHAN</span>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-start gap-3">
                            <div class="size-11 shrink-0 overflow-hidden rounded-xl border border-border bg-background">
                                <img src="{{ img_url($b->logo, $b->slug.'logo') }}" alt="" class="size-full object-cover" />
                            </div>
                            <div class="min-w-0">
                                <h3 class="truncate font-semibold">{{ $b->name }}</h3>
                                <p class="truncate text-xs text-muted-foreground">{{ $b->owner }}</p>
                            </div>
                        </div>

                        <p class="mt-3 line-clamp-2 text-sm text-muted-foreground">{{ $b->description }}</p>

                        <div class="mt-3 flex items-center justify-between">
                            @if ($b->category)<x-ui.badge variant="muted">{{ $b->category->name }}</x-ui.badge>@endif
                            <span class="text-xs text-muted-foreground">{{ $b->products->count() }} produk</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="sm:col-span-2 lg:col-span-3">
                    <x-empty-state icon="store" title="Usaha tidak ditemukan" message="Coba ubah kata kunci atau kategori." />
                </div>
            @endforelse
        </div>

        @if ($businesses->hasPages())
            <div class="mt-8">{{ $businesses->links() }}</div>
        @endif

        <div class="mt-10 rounded-2xl border border-primary/25 bg-primary/5 p-6 text-center">
            <i data-lucide="store" class="mx-auto size-8 text-primary"></i>
            <h2 class="mt-3 text-lg font-bold">Punya usaha? Daftarkan di sini</h2>
            <p class="mt-1.5 text-sm text-muted-foreground">Jamaah Masjid Al-Ikhlash dapat mempromosikan usahanya secara gratis.</p>
            <a href="{{ route('kontak') }}" wire:navigate class="mt-4 inline-block">
                <x-ui.button icon="plus">Ajukan Pendaftaran</x-ui.button>
            </a>
        </div>
    </div>
</div>
