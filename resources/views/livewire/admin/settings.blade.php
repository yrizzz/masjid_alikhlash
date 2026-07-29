<form wire:submit="save" class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight">Pengaturan Sistem</h1>
            <p class="text-sm text-muted-foreground">Konfigurasi umum, donasi, notifikasi, dan sosial media.</p>
        </div>
        <x-ui.button type="submit" icon="save">Simpan Semua</x-ui.button>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        @foreach ($groups as $group => $fields)
            <x-ui.card :title="$group">
                <div class="space-y-4">
                    @foreach ($fields as $key => [$label, $type, $help])
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium">{{ $label }}</label>
                            @if ($type === 'textarea')
                                <textarea wire:model="form.{{ $key }}" rows="3"
                                          class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                            @else
                                <input type="{{ $type }}" wire:model="form.{{ $key }}"
                                       class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                            @endif
                            @if ($help)<p class="text-xs text-muted-foreground">{{ $help }}</p>@endif
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        @endforeach
    </div>
</form>
