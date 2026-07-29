<div class="space-y-5">
    {{-- Filter --}}
    <x-ui.card padded="false">
        <div class="flex flex-wrap items-end gap-3 p-4">
            <div class="flex gap-1 rounded-lg bg-muted p-1">
                @foreach (['hari' => 'Hari Ini', 'pekan' => 'Pekan', 'bulan' => 'Bulan', 'tahun' => 'Tahun'] as $key => $label)
                    <button wire:click="applyPreset('{{ $key }}')" type="button"
                            class="rounded-md px-3 py-1.5 text-sm font-medium transition-colors {{ $preset === $key ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="space-y-1">
                <label class="block text-xs font-medium text-muted-foreground">Dari</label>
                <input type="date" wire:model.live="from" class="h-9 rounded-lg border border-input bg-background px-2 text-sm" />
            </div>
            <div class="space-y-1">
                <label class="block text-xs font-medium text-muted-foreground">Sampai</label>
                <input type="date" wire:model.live="to" class="h-9 rounded-lg border border-input bg-background px-2 text-sm" />
            </div>

            <div class="ms-auto flex gap-2">
                <x-ui.button variant="outline" size="sm" icon="download"
                             :href="route('admin.export', ['type' => 'keuangan', 'from' => $from, 'to' => $to])">Unduh CSV</x-ui.button>
                <x-ui.button variant="outline" size="sm" icon="printer" onclick="window.print()">Cetak</x-ui.button>
            </div>
        </div>
    </x-ui.card>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-ui.stat label="Total Pemasukan" :value="rupiah($income)" icon="trending-up" tone="success" />
        <x-ui.stat label="Total Pengeluaran" :value="rupiah($expense)" icon="trending-down" tone="destructive" />
        <x-ui.stat label="Selisih (Surplus/Defisit)" :value="rupiah($balance)" icon="scale" :tone="$balance >= 0 ? 'primary' : 'warning'" />
    </div>

    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        <x-ui.card class="xl:col-span-2" title="Arus Kas Harian">
            <div class="h-72"><canvas id="financeChart"></canvas></div>
        </x-ui.card>

        <x-ui.card title="Pengeluaran per Kategori">
            <div class="space-y-3">
                @php $max = $byCategory->max('total') ?: 1; @endphp
                @forelse ($byCategory as $c)
                    <div>
                        <div class="flex justify-between text-sm">
                            <span class="truncate font-medium">{{ $c['name'] }}</span>
                            <span class="shrink-0 tabular-nums text-muted-foreground">{{ rupiah_short($c['total']) }}</span>
                        </div>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-muted">
                            <div class="h-full rounded-full bg-destructive/70" style="width: {{ round($c['total'] / $max * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-muted-foreground">Tidak ada pengeluaran pada rentang ini.</p>
                @endforelse
            </div>
        </x-ui.card>
    </div>

    <x-ui.card title="Saldo per Rekening">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($accounts as $a)
                <div class="rounded-xl border border-border p-4">
                    <div class="flex items-center gap-2 text-muted-foreground">
                        <i data-lucide="{{ $a->type === 'bank' ? 'landmark' : 'wallet' }}" class="size-4"></i>
                        <span class="truncate text-sm">{{ $a->name }}</span>
                    </div>
                    <p class="mt-2 text-lg font-bold">{{ rupiah($a->balance) }}</p>
                    @if ($a->number)<p class="text-xs text-muted-foreground">{{ $a->number }}</p>@endif
                </div>
            @endforeach
        </div>
    </x-ui.card>

    <x-ui.card title="Rincian Transaksi" padded="false">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-muted/40 text-xs uppercase text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 text-start">Tanggal</th>
                        <th class="px-4 py-3 text-start">Keterangan</th>
                        <th class="px-4 py-3 text-start">Kategori</th>
                        <th class="px-4 py-3 text-start">Rekening</th>
                        <th class="px-4 py-3 text-end">Masuk</th>
                        <th class="px-4 py-3 text-end">Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($rows as $t)
                        <tr class="hover:bg-muted/40">
                            <td class="whitespace-nowrap px-4 py-3 text-muted-foreground">{{ tanggal_id($t->date, false) }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $t->description }}</p>
                                <p class="font-mono text-xs text-muted-foreground">{{ $t->code }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if ($t->category)<x-ui.badge variant="muted">{{ $t->category->name }}</x-ui.badge>@endif
                            </td>
                            <td class="px-4 py-3 text-muted-foreground">{{ $t->account?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-end font-semibold tabular-nums text-success">
                                {{ $t->type === 'in' ? rupiah($t->amount) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-end font-semibold tabular-nums text-destructive">
                                {{ $t->type === 'out' ? rupiah($t->amount) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-12 text-center text-muted-foreground">Tidak ada transaksi pada rentang ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($rows->hasPages())
            <div class="border-t border-border px-4 py-3">{{ $rows->links() }}</div>
        @endif
    </x-ui.card>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', drawFinanceChart);
    document.addEventListener('DOMContentLoaded', drawFinanceChart, { once: true });
    document.addEventListener('livewire:update', drawFinanceChart);

    function drawFinanceChart() {
        const el = document.getElementById('financeChart');
        if (!el || !window.Chart) return;
        const existing = Chart.getChart(el);
        if (existing) existing.destroy();

        new Chart(el, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [
                    { label: 'Pemasukan',   data: @json($inSeries),  borderColor: 'hsl(142 71% 45%)', backgroundColor: 'hsl(142 71% 45% / .12)', fill: true, tension: .35, borderWidth: 2, pointRadius: 0 },
                    { label: 'Pengeluaran', data: @json($outSeries), borderColor: 'hsl(347 77% 50%)', backgroundColor: 'hsl(347 77% 50% / .10)', fill: true, tension: .35, borderWidth: 2, pointRadius: 0 },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } } },
                scales: { y: { ticks: { callback: v => v >= 1e6 ? (v / 1e6) + ' jt' : v.toLocaleString('id-ID') } } },
            },
        });
    }
</script>
@endpush
