<div>
    <x-page-hero title="Arah Kiblat" icon="compass"
                 subtitle="Dihitung dari koordinat masjid menuju Ka'bah, Makkah Al-Mukarramah." />

    <div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div x-data="qibla({{ $direction }})" class="grid gap-8 lg:grid-cols-2 lg:items-center">
            {{-- Kompas --}}
            <div class="mx-auto w-full max-w-sm">
                <div class="relative aspect-square rounded-full border-8 border-border bg-card shadow-inner">
                    {{-- Mata angin --}}
                    @foreach (['U' => 0, 'T' => 90, 'S' => 180, 'B' => 270] as $dir => $deg)
                        <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-sm font-bold text-muted-foreground"
                              style="transform: translate(-50%,-50%) rotate({{ $deg }}deg) translateY(-8.5rem) rotate(-{{ $deg }}deg)">{{ $dir }}</span>
                    @endforeach

                    {{-- Garis derajat --}}
                    @for ($i = 0; $i < 36; $i++)
                        <span class="absolute left-1/2 top-1/2 h-2 w-px bg-border"
                              style="transform: translate(-50%,-50%) rotate({{ $i * 10 }}deg) translateY(-7.6rem)"></span>
                    @endfor

                    {{-- Jarum kiblat --}}
                    <div class="absolute inset-0 transition-transform duration-300"
                         :style="`transform: rotate(${needle}deg)`">
                        <div class="absolute left-1/2 top-1/2 flex -translate-x-1/2 -translate-y-full flex-col items-center">
                            <span class="grid size-9 place-items-center rounded-full bg-primary text-primary-foreground shadow-lg">
                                <i data-lucide="navigation" class="size-4"></i>
                            </span>
                            <span class="h-24 w-1 rounded-full bg-gradient-to-b from-primary to-primary/20"></span>
                        </div>
                    </div>

                    <span class="absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 rounded-full bg-foreground"></span>
                </div>

                <div class="mt-5 text-center">
                    <p class="font-mono text-3xl font-bold" x-text="readable + '°'">{{ number_format($direction, 1) }}°</p>
                    <p class="text-sm text-muted-foreground" x-show="!supported">dari arah utara sejati</p>
                    <p class="text-sm text-primary" x-show="supported" x-cloak>mengikuti kompas perangkat</p>
                </div>

                <button @click="enable()" x-show="!supported" class="mx-auto mt-4 block">
                    <x-ui.button icon="smartphone" variant="outline">Aktifkan Kompas Perangkat</x-ui.button>
                </button>
                <p x-show="permission === 'denied' || permission === 'unsupported'" x-cloak class="mt-3 text-center text-xs text-muted-foreground">
                    Perangkat tidak mendukung sensor kompas, atau izin ditolak. Gunakan nilai derajat di atas dengan kompas manual.
                </p>
            </div>

            {{-- Info --}}
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    @foreach ([
                        ['Arah Kiblat', number_format($direction, 2).'°', 'compass'],
                        ['Jarak ke Ka\'bah', number_format($distance, 0, ',', '.').' km', 'route'],
                        ['Latitude', number_format($coords['lat'], 6), 'map-pin'],
                        ['Longitude', number_format($coords['lng'], 6), 'map-pin'],
                    ] as [$label, $value, $icon])
                        <div class="rounded-2xl border border-border bg-card p-4">
                            <i data-lucide="{{ $icon }}" class="size-4 text-primary"></i>
                            <p class="mt-2 text-xs text-muted-foreground">{{ $label }}</p>
                            <p class="font-mono text-sm font-bold">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="flex items-center gap-2 font-semibold"><i data-lucide="info" class="size-4 text-primary"></i>Cara menggunakan</h2>
                    <ol class="mt-3 space-y-2 text-sm text-muted-foreground">
                        <li class="flex gap-2"><span class="font-semibold text-foreground">1.</span> Letakkan ponsel mendatar, jauh dari benda logam atau magnet.</li>
                        <li class="flex gap-2"><span class="font-semibold text-foreground">2.</span> Tekan “Aktifkan Kompas Perangkat” dan izinkan akses sensor.</li>
                        <li class="flex gap-2"><span class="font-semibold text-foreground">3.</span> Putar badan sampai jarum hijau mengarah lurus ke atas.</li>
                        <li class="flex gap-2"><span class="font-semibold text-foreground">4.</span> Bila sensor tidak tersedia, pakai kompas biasa dan cari sudut {{ number_format($direction, 1) }}° dari utara.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
