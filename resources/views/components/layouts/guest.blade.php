@props([
    'title' => 'Masuk',
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ setting('name', config('masjid.name')) }}</title>
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <meta name="theme-color" content="#f97316">

    <script>
        (function () {
            try {
                var t = localStorage.getItem('ak_theme');
                document.documentElement.classList.toggle('dark',
                    t === '"dark"' || (t === null && matchMedia('(prefers-color-scheme: dark)').matches));
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body x-data class="min-h-screen bg-background font-sans text-foreground antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">
        {{-- Panel identitas masjid --}}
        <div class="relative hidden overflow-hidden bg-[#1f0d03] p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="pointer-events-none absolute inset-0"
                 style="background:
                    radial-gradient(55rem 45rem at 110% -10%, hsl(25 95% 45% / .45), transparent 62%),
                    radial-gradient(40rem 40rem at -15% 115%, hsl(20 90% 35% / .40), transparent 60%);"></div>
            <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
                 style="background-image:
                    repeating-linear-gradient(45deg, #fff 0 1px, transparent 1px 22px),
                    repeating-linear-gradient(-45deg, #fff 0 1px, transparent 1px 22px);"></div>

            <div class="relative z-10 flex items-center gap-3">
                <span class="grid size-12 place-items-center rounded-2xl bg-white/10 backdrop-blur-sm">
                    <i data-lucide="moon-star" class="size-6"></i>
                </span>
                <div>
                    <p class="text-lg font-bold tracking-tight">{{ setting('name', config('masjid.name')) }}</p>
                    <p class="text-xs text-white/60">{{ config('masjid.village') }}, {{ config('masjid.city') }}</p>
                </div>
            </div>

            <div class="relative z-10 max-w-md">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[0.7rem] font-semibold uppercase tracking-wider">
                    <i data-lucide="badge-check" class="size-3.5"></i>{{ config('masjid.status') }}
                </span>
                <h2 class="mt-5 text-3xl font-bold leading-tight">Ruang kerja pengurus dan jamaah.</h2>
                <p class="mt-3 text-white/70">
                    Kelola jadwal ibadah, kajian, keuangan, TPQ, dan layanan jamaah dalam satu tempat —
                    atau masuk sebagai jamaah untuk memantau donasi dan kegiatan Anda.
                </p>
                <ul class="mt-8 space-y-3">
                    @foreach (['Laporan keuangan terbuka & realtime', 'Jadwal sholat otomatis dengan iqomah', 'Donasi dan zakat tercatat rapi', 'Kartu anggota digital ber-QR'] as $f)
                        <li class="flex items-center gap-3 text-sm text-white/85">
                            <span class="grid size-6 shrink-0 place-items-center rounded-full bg-white/10"><i data-lucide="check" class="size-3.5"></i></span>{{ $f }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="relative z-10 text-sm text-white/45">
                © {{ date('Y') }} {{ setting('name', config('masjid.name')) }} · {{ config('masjid.sk_number') }}
            </p>
        </div>

        {{-- Panel formulir --}}
        <div class="relative flex flex-col items-center justify-center p-6 sm:p-10">
            <div class="absolute end-4 top-4 flex items-center gap-1">
                <a href="{{ route('home') }}" title="Kembali ke website"
                   class="rounded-lg p-2 text-muted-foreground hover:bg-accent hover:text-foreground">
                    <i data-lucide="house" class="size-5"></i>
                </a>
                <button type="button" @click="$store.ui.toggleTheme()" title="Ganti tema"
                        class="rounded-lg p-2 text-muted-foreground hover:bg-accent hover:text-foreground">
                    <i data-lucide="sun" class="size-5" x-show="$store.ui.isDark" x-cloak></i>
                    <i data-lucide="moon" class="size-5" x-show="!$store.ui.isDark"></i>
                </button>
            </div>

            <div class="w-full max-w-sm">
                <a href="{{ route('home') }}" class="mb-8 flex items-center justify-center gap-2.5 lg:hidden">
                    <span class="grid size-10 place-items-center rounded-xl bg-gradient-to-br from-primary to-amber-900 text-white">
                        <i data-lucide="moon-star" class="size-5"></i>
                    </span>
                    <span class="text-lg font-bold">{{ setting('name', config('masjid.name')) }}</span>
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>

    <x-ui.toaster />
    @livewireScripts
    @stack('scripts')
</body>
</html>
