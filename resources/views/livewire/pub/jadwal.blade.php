<div>
    <x-page-hero title="Jadwal Sholat" icon="clock"
                 subtitle="Dihitung otomatis untuk koordinat {{ config('masjid.village') }}, {{ config('masjid.city') }} — standar Kemenag RI (Subuh 20°, Isya 18°) plus ihtiyat." />

    <div class="mx-auto max-w-7xl w-full min-w-0 px-4 py-10 sm:px-6 lg:px-8">
        {{-- Countdown besar --}}
        <div class="overflow-hidden rounded-3xl border border-amber-700/20 bg-gradient-to-br from-amber-950/20 via-card to-card p-6 sm:p-8 card-transition">
            <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center w-full min-w-0">
                <div class="w-full min-w-0">
                    <p class="text-sm text-muted-foreground">{{ tanggal_id() }} · {{ $hijri['formatted'] }}</p>
                    <p class="mt-3 text-sm font-bold uppercase tracking-[0.18em] text-amber-500">Menuju {{ $prayer['next_label'] }}</p>

                    <div x-data="countdown({{ $prayer['seconds_left'] }})" class="mt-2 flex items-baseline gap-2">
                        <span class="font-outfit text-5xl font-extrabold tabular-nums sm:text-6xl text-foreground" x-text="parts.h"></span>
                        <span class="text-3xl font-light text-muted-foreground">:</span>
                        <span class="font-outfit text-5xl font-extrabold tabular-nums sm:text-6xl text-foreground" x-text="parts.m"></span>
                        <span class="text-3xl font-light text-muted-foreground">:</span>
                        <span class="font-outfit text-5xl font-extrabold tabular-nums sm:text-6xl text-foreground" x-text="parts.s"></span>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                        <x-ui.badge variant="default" dot>Sedang berlangsung: {{ $prayer['current_label'] }}</x-ui.badge>
                        @if ($prayer['in_iqomah'])
                            <x-ui.badge variant="warning">Iqomah pukul {{ $prayer['iqomah_at']->format('H:i') }}</x-ui.badge>
                        @endif
                        <span class="text-muted-foreground">Adzan {{ $prayer['next_label'] }} pukul {{ $prayer['next_time']->format('H:i') }} WIB</span>
                    </div>

                    <div class="mt-4 max-w-md">
                        <div class="fund-bar"><span style="width: {{ $prayer['progress'] }}%"></span></div>
                        <p class="mt-1.5 text-xs text-muted-foreground font-medium">{{ $prayer['progress'] }}% waktu {{ $prayer['current_label'] }} telah berjalan</p>
                    </div>
                </div>

                <div class="grid grid-cols-4 gap-2 sm:grid-cols-8 lg:w-[26rem] lg:grid-cols-4 w-full min-w-0">
                    @foreach ($prayer['all'] as $key => $time)
                        <div class="rounded-2xl border p-3 text-center transition-all {{ $prayer['current'] === $key ? 'border-amber-500 bg-amber-500/15 shadow-md' : 'border-border bg-background' }}">
                            <p class="text-[0.7rem] font-bold text-muted-foreground">{{ $prayers[$key] }}</p>
                            <p class="mt-0.5 font-outfit text-sm font-extrabold">{{ $time->format('H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Jadwal bulanan --}}
        <div class="mt-8 overflow-hidden rounded-2xl border border-border bg-card shadow-sm w-full min-w-0">
            <div class="flex items-center justify-between gap-3 border-b border-border px-5 py-4">
                <div>
                    <h2 class="font-bold">Jadwal {{ $label }}</h2>
                    <p class="text-xs text-muted-foreground">Waktu dalam WIB, sudah termasuk ihtiyat.</p>
                </div>
                <div class="flex gap-1">
                    <button wire:click="shift(-1)" class="rounded-lg border border-border p-2 hover:bg-accent"><i data-lucide="chevron-left" class="size-4"></i></button>
                    <button wire:click="shift(1)" class="rounded-lg border border-border p-2 hover:bg-accent"><i data-lucide="chevron-right" class="size-4"></i></button>
                </div>
            </div>

            <div class="overflow-x-auto w-full max-w-full">
                <table class="w-full min-w-[42rem] text-sm">
                    <thead class="bg-muted/40 text-xs uppercase text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 text-start">Tanggal</th>
                            @foreach ($prayers as $p)
                                <th class="px-3 py-3 text-center">{{ $p }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border font-outfit tabular-nums">
                        @foreach ($schedule as $day => $times)
                            <tr class="{{ $times['date']->isToday() ? 'bg-amber-500/10 font-bold' : 'hover:bg-muted/30' }}">
                                <td class="whitespace-nowrap px-4 py-2.5 font-sans">
                                    {{ $times['date']->translatedFormat('D, d M') }}
                                    @if ($times['date']->isToday())
                                        <span class="ms-1.5 rounded-full bg-amber-500 px-2 py-0.5 text-[0.6rem] font-bold text-stone-950">HARI INI</span>
                                    @endif
                                </td>
                                @foreach ($prayers as $key => $p)
                                    <td class="px-3 py-2.5 text-center">{{ $times[$key]->format('H:i') }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-3">
            <a href="{{ route('imam') }}" wire:navigate class="flex items-center gap-3 rounded-2xl border border-border bg-card p-5 card-transition">
                <span class="grid size-11 place-items-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400"><i data-lucide="user-check" class="size-5"></i></span>
                <span><span class="block font-semibold">Jadwal Imam</span><span class="block text-xs text-muted-foreground">Imam, muadzin & cadangan</span></span>
            </a>
            <a href="{{ route('jumat') }}" wire:navigate class="flex items-center gap-3 rounded-2xl border border-border bg-card p-5 card-transition">
                <span class="grid size-11 place-items-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400"><i data-lucide="mic-vocal" class="size-5"></i></span>
                <span><span class="block font-semibold">Khatib Jumat</span><span class="block text-xs text-muted-foreground">Tema & jadwal khatib</span></span>
            </a>
            <a href="{{ route('kiblat') }}" wire:navigate class="flex items-center gap-3 rounded-2xl border border-border bg-card p-5 card-transition">
                <span class="grid size-11 place-items-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400"><i data-lucide="compass" class="size-5"></i></span>
                <span><span class="block font-semibold">Arah Kiblat</span><span class="block text-xs text-muted-foreground">Kompas digital</span></span>
            </a>
        </div>
    </div>
</div>
