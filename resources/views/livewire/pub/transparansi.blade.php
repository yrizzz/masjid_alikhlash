<div>
    <x-page-hero title="Transparansi Keuangan" icon="chart-pie"
                 subtitle="Seluruh pemasukan dan pengeluaran masjid terbuka untuk jamaah — realtime, dapat disaring, dan bisa diunduh." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- Ringkasan --}}
        <div class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-amber-500/25 bg-amber-500/5 p-5 card-lift-sm">
                <i data-lucide="trending-up" class="size-5 text-amber-600 dark:text-amber-400"></i>
                <p class="mt-3 text-xs text-muted-foreground">Pemasukan</p>
                <p class="text-xl font-bold font-outfit text-amber-600 dark:text-amber-400 mt-0.5">{{ rupiah($income) }}</p>
            </div>
            <div class="rounded-2xl border border-rose-500/25 bg-rose-500/5 p-5 card-lift-sm">
                <i data-lucide="trending-down" class="size-5 text-rose-600 dark:text-rose-400"></i>
                <p class="mt-3 text-xs text-muted-foreground">Pengeluaran</p>
                <p class="text-xl font-bold font-outfit text-rose-600 dark:text-rose-400 mt-0.5">{{ rupiah($expense) }}</p>
            </div>
            <div class="rounded-2xl border border-amber-500/25 bg-amber-500/5 p-5 card-lift-sm">
                <i data-lucide="wallet" class="size-5 text-amber-500"></i>
                <p class="mt-3 text-xs text-muted-foreground">Saldo Seluruh Rekening</p>
                <p class="text-xl font-bold font-outfit text-amber-600 dark:text-amber-400 mt-0.5">{{ rupiah($balance) }}</p>
            </div>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[1.5fr_1fr]">
            <div class="rounded-2xl border border-border bg-card p-5 card-transition">
                <h2 class="font-bold text-foreground">Arus Kas 12 Bulan Terakhir</h2>
                <div class="mt-4 h-64"><canvas id="publicFinanceChart"></canvas></div>
            </div>

            <div class="rounded-2xl border border-border bg-card p-5 card-transition">
                <h2 class="font-bold text-foreground">Alokasi Pengeluaran</h2>
                <p class="text-xs text-muted-foreground">Pada rentang tanggal terpilih</p>
                <div class="mt-4 space-y-3">
                    @php $max = $byCategory->max('total') ?: 1; @endphp
                    @forelse ($byCategory as $c)
                        <div>
                            <div class="flex justify-between text-sm">
                                <span class="truncate font-medium">{{ $c['name'] }}</span>
                                <span class="shrink-0 font-bold font-outfit tabular-nums text-amber-600 dark:text-amber-400">{{ rupiah_short($c['total']) }}</span>
                            </div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-muted">
                                <div class="h-full rounded-full bg-amber-500" style="width: {{ round($c['total'] / $max * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-muted-foreground">Belum ada pengeluaran tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="mt-8 rounded-2xl border border-border bg-card p-4">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex gap-1 rounded-lg bg-muted p-1">
                    @foreach (['hari' => 'Hari Ini', 'bulan' => 'Bulan Ini', 'tahun' => 'Tahun Ini', 'semua' => 'Semua'] as $key => $label)
                        <button wire:click="setRange('{{ $key }}')"
                                class="rounded-md px-3 py-1.5 text-sm font-medium transition-colors {{ $range === $key ? 'bg-background shadow-sm' : 'text-muted-foreground hover:text-foreground' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <input type="date" wire:model.live="from" class="h-9 rounded-lg border border-input bg-background px-2 text-sm" />
                <input type="date" wire:model.live="to" class="h-9 rounded-lg border border-input bg-background px-2 text-sm" />

                <select wire:model.live="type" class="h-9 rounded-lg border border-input bg-background px-2 text-sm">
                    <option value="">Semua jenis</option>
                    <option value="in">Pemasukan</option>
                    <option value="out">Pengeluaran</option>
                </select>

                <select wire:model.live="categoryId" class="h-9 rounded-lg border border-input bg-background px-2 text-sm">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>

                <div class="ms-auto flex gap-2">
                    <a href="{{ route('admin.export', ['type' => 'keuangan', 'from' => $from, 'to' => $to]) }}" @guest class="pointer-events-none opacity-40" @endguest>
                        <x-ui.button size="sm" variant="outline" icon="download">Unduh CSV</x-ui.button>
                    </a>
                    <button onclick="window.print()"><x-ui.button size="sm" variant="outline" icon="printer">Cetak</x-ui.button></button>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="mt-4 overflow-hidden rounded-2xl border border-border bg-card">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[44rem] text-sm">
                    <thead class="border-b border-border bg-muted/40 text-xs uppercase text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 text-start">Tanggal</th>
                            <th class="px-4 py-3 text-start">Keterangan</th>
                            <th class="px-4 py-3 text-start">Kategori</th>
                            <th class="px-4 py-3 text-end">Masuk</th>
                            <th class="px-4 py-3 text-end">Keluar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse ($rows as $t)
                            <tr class="hover:bg-muted/30">
                                <td class="whitespace-nowrap px-4 py-3 text-muted-foreground">{{ tanggal_id($t->date, false) }}</td>
                                <td class="px-4 py-3">
                                    <p class="font-medium">{{ $t->description }}</p>
                                    <p class="font-mono text-xs text-muted-foreground">{{ $t->code }}</p>
                                </td>
                                <td class="px-4 py-3">@if ($t->category)<x-ui.badge variant="muted">{{ $t->category->name }}</x-ui.badge>@endif</td>
                                <td class="px-4 py-3 text-end font-semibold tabular-nums text-amber-600 dark:text-amber-400">{{ $t->type === 'in' ? rupiah($t->amount) : '—' }}</td>
                                <td class="px-4 py-3 text-end font-semibold tabular-nums text-rose-600 dark:text-rose-400">{{ $t->type === 'out' ? rupiah($t->amount) : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-14 text-center text-muted-foreground">Tidak ada transaksi pada rentang ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($rows->hasPages())
                <div class="border-t border-border px-4 py-3">{{ $rows->links() }}</div>
            @endif
        </div>

        {{-- Rekening --}}
        <h2 class="mt-10 text-lg font-bold">Rekening Resmi Masjid</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($accounts as $a)
                <div class="rounded-2xl border border-border bg-card p-5">
                    <div class="flex items-center gap-2 text-muted-foreground">
                        <i data-lucide="{{ $a->type === 'bank' ? 'landmark' : 'wallet' }}" class="size-4"></i>
                        <span class="truncate text-sm">{{ $a->name }}</span>
                    </div>
                    @if ($a->number)<p class="mt-2 font-mono text-sm">{{ $a->number }}</p>@endif
                    <p class="mt-1 text-lg font-bold">{{ rupiah($a->balance) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:navigated', drawPublicFinance);
    document.addEventListener('DOMContentLoaded', drawPublicFinance, { once: true });

    function drawPublicFinance() {
        const el = document.getElementById('publicFinanceChart');
        if (!el || !window.Chart) return;
        const ex = Chart.getChart(el);
        if (ex) ex.destroy();

        new Chart(el, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [
                    { label: 'Pemasukan',   data: @json($inSeries),  backgroundColor: 'hsl(160 70% 40% / .8)', borderRadius: 5 },
                    { label: 'Pengeluaran', data: @json($outSeries), backgroundColor: 'hsl(350 75% 55% / .75)', borderRadius: 5 },
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
    }
</script>
@endpush
