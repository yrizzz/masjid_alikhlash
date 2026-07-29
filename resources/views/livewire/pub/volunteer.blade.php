<div>
    <x-page-hero title="Jadi Relawan Masjid" icon="hand-helping"
                 subtitle="Salurkan tenaga, waktu, dan keahlian Anda untuk memakmurkan Masjid Al-Ikhlash." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
            <div>
                @if ($done)
                    <div class="rounded-2xl border border-primary/30 bg-primary/5 p-8 text-center">
                        <i data-lucide="party-popper" class="mx-auto size-10 text-primary"></i>
                        <h2 class="mt-4 text-xl font-bold">Jazakumullahu khairan!</h2>
                        <p class="mt-2 text-sm text-muted-foreground">
                            Pendaftaran Anda sudah kami terima. Koordinator relawan akan menghubungi lewat WhatsApp
                            untuk penempatan sesuai minat dan waktu Anda.
                        </p>
                        <div class="mt-6 flex flex-wrap justify-center gap-2">
                            <x-ui.button :href="route('program')" variant="outline" icon="sparkles">Lihat Program</x-ui.button>
                            <x-ui.button :href="route('home')" icon="house">Kembali ke Beranda</x-ui.button>
                        </div>
                    </div>
                @else
                    <form wire:submit="submit" class="rounded-2xl border border-border bg-card p-6">
                        <h2 class="font-bold">Formulir Pendaftaran Relawan</h2>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <x-ui.input wire:model="name" label="Nama Lengkap" :error="$errors->first('name')" />
                            <x-ui.input wire:model="phone" label="Nomor WhatsApp" :error="$errors->first('phone')" />
                            <x-ui.input wire:model="email" label="Email (opsional)" />
                            <x-ui.input wire:model="availability" label="Ketersediaan Waktu" hint="Contoh: Akhir pekan, sore hari kerja." />
                        </div>

                        <div class="mt-5 space-y-1.5">
                            <label class="block text-sm font-medium">Bidang yang Diminati <span class="text-destructive">*</span></label>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach ($interestOptions as $option)
                                    <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-border p-3 text-sm transition-colors hover:bg-accent">
                                        <input type="checkbox" wire:model="interests" value="{{ $option }}" class="size-4 rounded border-input" />
                                        {{ $option }}
                                    </label>
                                @endforeach
                            </div>
                            @error('interests')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                        </div>

                        <div class="mt-5 grid gap-4">
                            <x-ui.input wire:model="skills" label="Keahlian Khusus" hint="Contoh: desain grafis, videografi, tukang listrik, mengajar." />

                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Alamat</label>
                                <textarea wire:model="address" rows="2" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Motivasi</label>
                                <textarea wire:model="motivation" rows="3" placeholder="Ceritakan singkat alasan Anda ingin bergabung."
                                          class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                            </div>
                        </div>

                        <x-ui.button type="submit" class="mt-6" size="lg" icon="hand-helping">Daftar Sebagai Relawan</x-ui.button>
                    </form>
                @endif
            </div>

            <aside class="space-y-5">
                <div class="rounded-2xl border border-primary/25 bg-primary/5 p-5 text-center">
                    <p class="text-3xl font-bold text-primary">{{ $total }}</p>
                    <p class="text-sm text-muted-foreground">relawan aktif saat ini</p>
                </div>

                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Program yang Membutuhkan Relawan</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($programs as $p)
                            <a href="{{ route('program.show', $p) }}" wire:navigate class="flex items-center gap-3">
                                <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
                                    <i data-lucide="{{ $p->icon }}" class="size-4"></i>
                                </span>
                                <span class="min-w-0 truncate text-sm font-medium">{{ $p->title }}</span>
                            </a>
                        @empty
                            <p class="text-sm text-muted-foreground">Belum ada program aktif.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
