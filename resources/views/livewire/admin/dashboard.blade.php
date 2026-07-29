<div class="space-y-6">
    {{-- Sambutan + waktu sholat --}}
    <div class="ak-card relative overflow-hidden p-6">
        <div class="pointer-events-none absolute inset-0 opacity-70"
             style="background: radial-gradient(40rem 30rem at 100% -20%, hsl(var(--primary)/0.18), transparent 60%);"></div>

        <div class="relative flex flex-wrap items-center justify-between gap-6">
            <div>
                <p class="text-sm text-muted-foreground">{{ tanggal_id() }} · {{ $hijri['formatted'] }}</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">
                    Assalamu'alaikum, {{ auth()->user()->name }}
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Ringkasan aktivitas {{ setting('name', config('masjid.name')) }} hari ini.
                </p>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-border bg-background/60 px-5 py-4 backdrop-blur">
                <div class="text-center">
                    <p class="text-xs uppercase tracking-wide text-muted-foreground">Berikutnya</p>
                    <p class="text-lg font-bold">{{ $prayer['next_label'] }}</p>
                </div>
                <div class="h-10 w-px bg-border"></div>
                <div class="text-center" x-data="countdown({{ $prayer['seconds_left'] }})">
                    <p class="font-mono text-2xl font-bold tabular-nums" x-text="display"></p>
                    <p class="text-xs text-muted-foreground">{{ $prayer['next_time']->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kartu statistik --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($stats as $s)
            <x-ui.stat :label="$s['label']" :value="$s['value']" :icon="$s['icon']" :tone="$s['tone']" />
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        {{-- Grafik keuangan --}}
        <x-ui.card class="xl:col-span-2" title="Arus Kas 12 Bulan" subtitle="Pemasukan vs pengeluaran (Rupiah)">
            <x-slot:actions>
                <x-ui.button size="sm" variant="outline" icon="download" :href="route('admin.export', 'keuangan')">CSV</x-ui.button>
            </x-slot:actions>
            <div class="h-72"><canvas id="cashflowChart"></canvas></div>
        </x-ui.card>

        {{-- Ringkasan bulan ini --}}
        <x-ui.card title="Bulan Ini" subtitle="{{ now()->translatedFormat('F Y') }}">
            <div class="space-y-4">
                <div class="rounded-xl border border-success/25 bg-success/8 p-4">
                    <p class="text-sm text-muted-foreground">Pemasukan</p>
                    <p class="mt-1 text-xl font-bold text-success">{{ rupiah($income) }}</p>
                </div>
                <div class="rounded-xl border border-destructive/25 bg-destructive/8 p-4">
                    <p class="text-sm text-muted-foreground">Pengeluaran</p>
                    <p class="mt-1 text-xl font-bold text-destructive">{{ rupiah($expense) }}</p>
                </div>
                <div class="rounded-xl border border-border bg-muted/40 p-4">
                    <p class="text-sm text-muted-foreground">Saldo Seluruh Rekening</p>
                    <p class="mt-1 text-xl font-bold">{{ rupiah($balance) }}</p>
                </div>
                <x-ui.button variant="outline" class="w-full" icon="chart-pie" :href="route('admin.finance.report')">
                    Buka Laporan Keuangan
                </x-ui.button>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        {{-- Campaign berjalan --}}
        <x-ui.card class="xl:col-span-2" title="Campaign Donasi Berjalan">
            <x-slot:actions>
                <x-ui.button size="sm" variant="ghost" iconEnd="arrow-right" :href="route('admin.campaigns')">Kelola</x-ui.button>
            </x-slot:actions>

            <div class="space-y-5">
                @forelse ($campaigns as $c)
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <p class="truncate font-medium">{{ $c->title }}</p>
                            <span class="shrink-0 text-sm font-semibold text-primary">{{ $c->progress }}%</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-muted">
                            <div class="h-full rounded-full bg-primary transition-all" style="width: {{ $c->progress }}%"></div>
                        </div>
                        <div class="mt-1.5 flex justify-between text-xs text-muted-foreground">
                            <span>{{ rupiah($c->collected) }} terkumpul</span>
                            <span>target {{ rupiah($c->target) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-muted-foreground">Belum ada campaign aktif.</p>
                @endforelse
            </div>
        </x-ui.card>

        {{-- Traffic --}}
        <x-ui.card title="Pengunjung Website" subtitle="14 hari terakhir">
            <div class="h-52"><canvas id="trafficChart"></canvas></div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <x-ui.card title="Donasi Terbaru">
            <div class="-my-2 divide-y divide-border">
                @forelse ($donations as $d)
                    <div class="flex items-center gap-3 py-3">
                        <x-ui.avatar :name="$d->display_name" size="sm" />
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium">{{ $d->display_name }}</p>
                            <p class="truncate text-xs text-muted-foreground">{{ $d->campaign?->title ?? 'Donasi Umum' }}</p>
                        </div>
                        <div class="text-end">
                            <p class="text-sm font-semibold">{{ rupiah_short($d->amount) }}</p>
                            <x-ui.badge :variant="$d->status === 'paid' ? 'success' : 'warning'" class="mt-0.5">
                                {{ $d->status === 'paid' ? 'Lunas' : 'Menunggu' }}
                            </x-ui.badge>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-muted-foreground">Belum ada donasi.</p>
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card title="Kajian Mendatang">
            <div class="-my-2 divide-y divide-border">
                @forelse ($kajians as $k)
                    <div class="flex items-center gap-3 py-3">
                        <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-center text-primary">
                            <div>
                                <p class="text-[0.65rem] font-semibold uppercase leading-none">{{ $k->start_at?->translatedFormat('M') }}</p>
                                <p class="text-sm font-bold leading-tight">{{ $k->start_at?->format('d') }}</p>
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium">{{ $k->title }}</p>
                            <p class="truncate text-xs text-muted-foreground">{{ $k->ustadz }} · {{ $k->start_at?->format('H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-muted-foreground">Belum ada kajian terjadwal.</p>
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card title="Booking Menunggu Persetujuan">
            <x-slot:actions>
                <x-ui.button size="sm" variant="ghost" iconEnd="arrow-right" :href="route('admin.bookings')">Semua</x-ui.button>
            </x-slot:actions>
            <div class="-my-2 divide-y divide-border">
                @forelse ($bookings as $b)
                    <div class="flex items-center gap-3 py-3">
                        <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-warning/15 text-[hsl(var(--warning))]">
                            <i data-lucide="door-open" class="size-4"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium">{{ $b->name }} · {{ $b->room?->name }}</p>
                            <p class="truncate text-xs text-muted-foreground">{{ tanggal_id($b->date, false) }} · {{ substr($b->start_time, 0, 5) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-muted-foreground">Tidak ada permintaan baru.</p>
                @endforelse
            </div>
        </x-ui.card>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', initDashboardCharts, { once: false });
    document.addEventListener('DOMContentLoaded', initDashboardCharts, { once: true });

    function initDashboardCharts() {
        const cash = document.getElementById('cashflowChart');
        const traffic = document.getElementById('trafficChart');
        if (!cash || !window.Chart) return;

        [cash, traffic].forEach(c => { const i = c && Chart.getChart(c); if (i) i.destroy(); });

        new Chart(cash, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    { label: 'Pemasukan',   data: @json($chartIn),  backgroundColor: 'hsl(142 71% 45% / .75)', borderRadius: 6 },
                    { label: 'Pengeluaran', data: @json($chartOut), backgroundColor: 'hsl(347 77% 50% / .70)', borderRadius: 6 },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } },
                    tooltip: { callbacks: { label: c => c.dataset.label + ': Rp ' + c.parsed.y.toLocaleString('id-ID') } },
                },
                scales: { y: { ticks: { callback: v => v >= 1e6 ? (v / 1e6) + ' jt' : v.toLocaleString('id-ID') } } },
            },
        });

        if (traffic) {
            new Chart(traffic, {
                type: 'line',
                data: {
                    labels: @json($trafficLabels),
                    datasets: [{
                        label: 'Kunjungan', data: @json($trafficData),
                        borderColor: 'hsl(var(--primary))', backgroundColor: 'hsl(var(--primary) / .12)',
                        fill: true, tension: .38, pointRadius: 0, borderWidth: 2,
                    }],
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } },
            });
        }
    }
</script>
@endpush
