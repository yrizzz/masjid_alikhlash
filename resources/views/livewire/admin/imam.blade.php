<form wire:submit="save" class="space-y-5">
    <x-ui.card title="Jadwal Imam, Muadzin & Cadangan" subtitle="Berlaku berulang setiap pekan. Kosongkan kolom imam untuk menghapus jadwal.">
        <x-slot:actions>
            <x-ui.button type="submit" icon="save">Simpan Jadwal</x-ui.button>
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[56rem] text-sm">
                <thead class="text-xs uppercase text-muted-foreground">
                    <tr class="border-b border-border">
                        <th class="px-3 py-2 text-start">Hari</th>
                        @foreach ($prayers as $key => $label)
                            <th class="px-3 py-2 text-start">
                                {{ $label }}
                                <span class="ms-1 font-mono font-normal normal-case">{{ $times[$key]?->format('H:i') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($days as $dayIndex => $dayName)
                        <tr>
                            <td class="whitespace-nowrap px-3 py-3 font-semibold">{{ $dayName }}</td>
                            @foreach ($prayers as $key => $label)
                                <td class="px-3 py-3">
                                    <div class="space-y-1.5">
                                        <input type="text" wire:model="grid.{{ $dayIndex }}.{{ $key }}.imam" placeholder="Imam"
                                               class="h-8 w-full min-w-[8rem] rounded-md border border-input bg-background px-2 text-xs focus:outline-none focus:ring-1 focus:ring-ring" />
                                        <input type="text" wire:model="grid.{{ $dayIndex }}.{{ $key }}.muadzin" placeholder="Muadzin"
                                               class="h-8 w-full rounded-md border border-input bg-background px-2 text-xs text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring" />
                                        <input type="text" wire:model="grid.{{ $dayIndex }}.{{ $key }}.backup" placeholder="Cadangan"
                                               class="h-8 w-full rounded-md border border-dashed border-input bg-background px-2 text-xs text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring" />
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-ui.card>
</form>
