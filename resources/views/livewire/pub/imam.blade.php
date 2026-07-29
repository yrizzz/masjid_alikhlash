<div>
    <x-page-hero title="Jadwal Imam & Muadzin" icon="user-check"
                 subtitle="Petugas sholat berjamaah lima waktu, berlaku berulang setiap pekan." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        {{-- Hari ini --}}
        <div class="rounded-2xl border border-primary/25 bg-primary/5 p-5">
            <p class="flex items-center gap-2 text-sm font-semibold text-primary">
                <i data-lucide="calendar-check" class="size-4"></i>Petugas hari ini — {{ $days[$today] }}, {{ tanggal_id(now(), false) }}
            </p>
            <div class="mt-4 grid gap-3 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ($prayers as $key => $label)
                    @php $row = ($schedules[$today] ?? collect())->firstWhere('prayer', $key); @endphp
                    <div class="rounded-xl bg-background p-4">
                        <div class="flex items-baseline justify-between">
                            <p class="text-sm font-semibold">{{ $label }}</p>
                            <p class="font-mono text-xs text-muted-foreground">{{ $times[$key]?->format('H:i') }}</p>
                        </div>
                        <p class="mt-2 text-sm">{{ $row->imam ?? '—' }}</p>
                        <p class="text-xs text-muted-foreground">Muadzin: {{ $row->muadzin ?? '—' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sepekan --}}
        <div class="mt-8 overflow-hidden rounded-2xl border border-border bg-card">
            <div class="border-b border-border px-5 py-4">
                <h2 class="font-bold">Jadwal Sepekan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[52rem] text-sm">
                    <thead class="bg-muted/40 text-xs uppercase text-muted-foreground">
                        <tr>
                            <th class="px-4 py-3 text-start">Hari</th>
                            @foreach ($prayers as $key => $label)
                                <th class="px-4 py-3 text-start">{{ $label }} <span class="ms-1 font-mono font-normal normal-case">{{ $times[$key]?->format('H:i') }}</span></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach ($days as $i => $dayName)
                            <tr class="{{ $i === $today ? 'bg-primary/5' : '' }}">
                                <td class="whitespace-nowrap px-4 py-3 font-semibold">{{ $dayName }}</td>
                                @foreach ($prayers as $key => $label)
                                    @php $row = ($schedules[$i] ?? collect())->firstWhere('prayer', $key); @endphp
                                    <td class="px-4 py-3">
                                        @if ($row)
                                            <p class="font-medium">{{ $row->imam }}</p>
                                            @if ($row->muadzin)<p class="text-xs text-muted-foreground">{{ $row->muadzin }}</p>@endif
                                            @if ($row->backup)<p class="text-xs text-muted-foreground/70">Cad: {{ $row->backup }}</p>@endif
                                        @else
                                            <span class="text-muted-foreground">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
