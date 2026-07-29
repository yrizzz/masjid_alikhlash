<div class="space-y-5">
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <form wire:submit="save" class="lg:col-span-1">
            <x-ui.card title="Parameter Perhitungan" subtitle="Standar Kemenag RI: Subuh 20°, Isya 18°.">
                <div class="space-y-4">
                    @foreach ([
                        'mosque_lat' => 'Latitude', 'mosque_lng' => 'Longitude',
                        'mosque_elevation' => 'Ketinggian (mdpl)', 'prayer_fajr_angle' => 'Sudut Subuh (°)',
                        'prayer_isha_angle' => 'Sudut Isya (°)', 'prayer_ihtiyat' => 'Ihtiyat (menit)',
                        'prayer_timezone' => 'Zona Waktu (UTC+)',
                    ] as $key => $label)
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium">{{ $label }}</label>
                            <input type="text" wire:model="params.{{ $key }}"
                                   class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                        </div>
                    @endforeach

                    <div class="border-t border-border pt-4">
                        <p class="mb-3 text-sm font-semibold">Jeda Iqomah (menit)</p>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach ($iqomah as $prayer => $value)
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-medium text-muted-foreground">{{ $prayers[$prayer] ?? ucfirst($prayer) }}</label>
                                    <input type="number" wire:model="iqomah.{{ $prayer }}"
                                           class="h-9 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <x-ui.button type="submit" class="w-full" icon="save">Simpan & Hitung Ulang</x-ui.button>

                    <x-ui.alert variant="info" title="Arah Kiblat">
                        {{ number_format($qibla, 2) }}° dari utara sejati.
                    </x-ui.alert>
                </div>
            </x-ui.card>
        </form>

        <x-ui.card class="lg:col-span-2" title="Jadwal {{ \Carbon\Carbon::create($year, $month)->translatedFormat('F Y') }}"
                   subtitle="Dihitung otomatis di server — tidak bergantung API luar.">
            <x-slot:actions>
                <select wire:model.live="month" class="h-9 rounded-lg border border-input bg-background px-2 text-sm">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create($year, $m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
                <select wire:model.live="year" class="h-9 rounded-lg border border-input bg-background px-2 text-sm">
                    @foreach (range(now()->year - 1, now()->year + 2) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </x-slot:actions>

            <div class="max-h-[34rem] overflow-auto">
                <table class="w-full text-sm">
                    <thead class="sticky top-0 bg-card text-xs uppercase text-muted-foreground">
                        <tr class="border-b border-border">
                            <th class="px-2 py-2 text-start">Tgl</th>
                            @foreach ($prayers as $label)
                                <th class="px-2 py-2 text-center">{{ $label }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border font-mono tabular-nums">
                        @foreach ($schedule as $day => $times)
                            <tr class="{{ $times['date']->isToday() ? 'bg-primary/8 font-semibold' : '' }}">
                                <td class="px-2 py-1.5">{{ $day }}</td>
                                @foreach ($prayers as $key => $label)
                                    <td class="px-2 py-1.5 text-center">{{ $times[$key]->format('H:i') }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-ui.card>
    </div>
</div>
