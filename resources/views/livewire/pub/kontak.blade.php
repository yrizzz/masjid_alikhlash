<div>
    <x-page-hero title="Hubungi Kami" icon="mail" subtitle="Sampaikan pertanyaan, usulan, atau permohonan kerja sama kepada takmir." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-2">
            <form wire:submit="submit" class="rounded-2xl border border-border bg-card p-6">
                <h2 class="font-bold">Kirim Pesan</h2>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <x-ui.input wire:model="name" label="Nama" :error="$errors->first('name')" />
                    <x-ui.input wire:model="phone" label="Nomor WhatsApp" />
                    <x-ui.input wire:model="email" label="Email" :error="$errors->first('email')" />
                    <x-ui.input wire:model="subject" label="Subjek" />
                </div>

                <div class="mt-4 space-y-1.5">
                    <label class="block text-sm font-medium">Pesan</label>
                    <textarea wire:model="message" rows="5"
                              class="w-full rounded-lg border {{ $errors->has('message') ? 'border-destructive' : 'border-input' }} bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                    @error('message')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                </div>

                <x-ui.button type="submit" class="mt-5" icon="send">Kirim Pesan</x-ui.button>

                @if ($sent)
                    <div class="mt-4"><x-ui.alert variant="success" title="Terkirim">Pesan Anda sudah kami terima. Terima kasih.</x-ui.alert></div>
                @endif
            </form>

            <div class="space-y-5">
                <div class="rounded-2xl border border-border bg-card p-6">
                    <h2 class="font-bold">Alamat & Kontak</h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <p class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="mt-0.5 size-4 shrink-0 text-primary"></i>
                            <span class="text-muted-foreground">{{ setting('address', config('masjid.address')) }}</span>
                        </p>
                        @if (setting('phone'))
                            <p class="flex items-center gap-2.5"><i data-lucide="phone" class="size-4 text-primary"></i>
                                <a href="tel:{{ setting('phone') }}" class="text-muted-foreground hover:text-foreground">{{ setting('phone') }}</a></p>
                        @endif
                        @if (setting('email'))
                            <p class="flex items-center gap-2.5"><i data-lucide="mail" class="size-4 text-primary"></i>
                                <a href="mailto:{{ setting('email') }}" class="text-muted-foreground hover:text-foreground">{{ setting('email') }}</a></p>
                        @endif
                    </div>

                    <a href="{{ config('masjid.maps_url') }}" target="_blank" class="mt-5 inline-block">
                        <x-ui.button variant="outline" icon="map-pin">Buka di Google Maps</x-ui.button>
                    </a>
                </div>

                <div class="overflow-hidden rounded-2xl border border-border">
                    <iframe class="h-64 w-full" style="border:0" loading="lazy" title="Peta"
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ config('masjid.lng') - 0.004 }},{{ config('masjid.lat') - 0.003 }},{{ config('masjid.lng') + 0.004 }},{{ config('masjid.lat') + 0.003 }}&layer=mapnik&marker={{ config('masjid.lat') }},{{ config('masjid.lng') }}"></iframe>
                </div>

                @if ($contacts->isNotEmpty())
                    <div class="rounded-2xl border border-border bg-card p-6">
                        <h2 class="font-bold">Kontak Pengurus</h2>
                        <div class="mt-4 space-y-3">
                            @foreach ($contacts as $p)
                                <div class="flex items-center gap-3">
                                    <x-ui.avatar :src="$p->photo ? img_url($p->photo) : null" :name="$p->name" size="sm" />
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium">{{ $p->name }}</p>
                                        <p class="truncate text-xs text-muted-foreground">{{ $p->position }}</p>
                                    </div>
                                    <a href="tel:{{ $p->phone }}" class="rounded-lg border border-border p-2 text-muted-foreground hover:bg-accent">
                                        <i data-lucide="phone" class="size-4"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
