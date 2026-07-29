<div>
    <x-page-hero title="Pertanyaan Umum" icon="circle-help" subtitle="Jawaban atas pertanyaan yang paling sering diajukan jamaah." />

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="relative">
            <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground"></i>
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Cari pertanyaan…"
                   class="h-12 w-full rounded-xl border border-input bg-background ps-10 pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
        </div>

        @forelse ($groups as $group => $items)
            <section class="mt-8">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-muted-foreground">{{ ucfirst($group) }}</h2>
                <div class="mt-3 divide-y divide-border overflow-hidden rounded-2xl border border-border bg-card">
                    @foreach ($items as $faq)
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="flex w-full items-center gap-3 p-5 text-start">
                                <span class="flex-1 font-medium">{{ $faq->question }}</span>
                                <i data-lucide="chevron-down" class="size-4 shrink-0 text-muted-foreground transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-collapse x-cloak>
                                <p class="px-5 pb-5 text-sm leading-relaxed text-muted-foreground">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @empty
            <div class="mt-8"><x-empty-state icon="circle-help" title="Tidak ditemukan" message="Coba kata kunci lain, atau kirim pertanyaan langsung ke pengurus." /></div>
        @endforelse

        <div class="mt-10 rounded-2xl border border-primary/25 bg-primary/5 p-6 text-center">
            <p class="font-semibold">Belum menemukan jawabannya?</p>
            <p class="mt-1 text-sm text-muted-foreground">Tanyakan pada asisten masjid atau hubungi pengurus langsung.</p>
            <div class="mt-4 flex flex-wrap justify-center gap-2">
                <x-ui.button :href="route('assistant')" icon="bot">Tanya Asisten</x-ui.button>
                <x-ui.button :href="route('kontak')" variant="outline" icon="mail">Hubungi Pengurus</x-ui.button>
            </div>
        </div>
    </div>
</div>
