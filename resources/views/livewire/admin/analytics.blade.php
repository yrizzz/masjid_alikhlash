<div class="space-y-5">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.stat label="Total Kunjungan" :value="number_format($total, 0, ',', '.')" icon="eye" tone="primary" />
        <x-ui.stat label="Pengunjung Unik" :value="number_format($unique, 0, ',', '.')" icon="users" tone="info" />
        <x-ui.stat label="Rentang" :value="$days.' hari'" icon="calendar" tone="success" />
    </div>

    <x-ui.card title="Tren Kunjungan">
        <x-slot:actions>
            <select wire:model.live="days" class="h-9 rounded-lg border border-input bg-background px-2 text-sm">
                <option value="7">7 hari</option><option value="30">30 hari</option><option value="90">90 hari</option>
            </select>
        </x-slot:actions>
        <div class="h-72"><canvas id="analyticsChart"></canvas></div>
    </x-ui.card>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <x-ui.card title="Halaman Terpopuler">
            <div class="-my-2 divide-y divide-border">
                @forelse ($popular as $p)
                    <div class="flex items-center justify-between gap-3 py-2.5">
                        <span class="truncate font-mono text-xs">{{ $p->path }}</span>
                        <span class="shrink-0 text-sm font-semibold tabular-nums">{{ number_format($p->hits, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-muted-foreground">Belum ada data kunjungan.</p>
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card title="Artikel Terbanyak Dibaca">
            <div class="-my-2 divide-y divide-border">
                @forelse ($articles as $a)
                    <div class="flex items-center justify-between gap-3 py-2.5">
                        <span class="truncate text-sm">{{ $a->title }}</span>
                        <span class="shrink-0 text-sm font-semibold tabular-nums">{{ $a->views }}</span>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-muted-foreground">Belum ada artikel.</p>
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card title="Kajian Terpopuler">
            <div class="-my-2 divide-y divide-border">
                @forelse ($kajians as $k)
                    <div class="flex items-center justify-between gap-3 py-2.5">
                        <span class="truncate text-sm">{{ $k->title }}</span>
                        <span class="shrink-0 text-sm font-semibold tabular-nums">{{ $k->views }}</span>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-muted-foreground">Belum ada kajian.</p>
                @endforelse
            </div>
        </x-ui.card>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', drawAnalytics);
    document.addEventListener('DOMContentLoaded', drawAnalytics, { once: true });
    document.addEventListener('livewire:update', drawAnalytics);

    function drawAnalytics() {
        const el = document.getElementById('analyticsChart');
        if (!el || !window.Chart) return;
        const ex = Chart.getChart(el);
        if (ex) ex.destroy();

        new Chart(el, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{ label: 'Kunjungan', data: @json($series), backgroundColor: 'hsl(var(--primary) / .7)', borderRadius: 5 }],
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } },
        });
    }
</script>
@endpush
