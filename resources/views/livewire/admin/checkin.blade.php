<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
    <x-ui.card title="Pemindai QR Check-in" subtitle="Arahkan pemindai QR ke kolom di bawah, atau ketik kode manual.">
        <form wire:submit="scan" class="space-y-4">
            <div class="relative">
                <i data-lucide="scan-line" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-4 size-5 text-primary"></i>
                <input type="text" wire:model="code" autofocus placeholder="KJ-XXXXXXXX atau nomor anggota"
                       class="h-14 w-full rounded-xl border-2 border-dashed border-primary/40 bg-primary/5 ps-12 pe-4 font-mono text-lg focus:border-primary focus:outline-none" />
            </div>
            <x-ui.button type="submit" class="w-full" size="lg" icon="check">Proses Check-in</x-ui.button>
        </form>

        @if ($result)
            <div class="mt-4">
                <x-ui.alert :variant="$result['ok'] ? 'success' : 'destructive'" :title="$result['title']">
                    {{ $result['body'] }}
                </x-ui.alert>
            </div>
        @endif

        <div class="mt-6 rounded-xl bg-muted/50 p-4 text-sm text-muted-foreground">
            <p class="font-medium text-foreground">Kode yang dikenali</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                <li>Kode pendaftaran kajian (diawali <code class="font-mono">KJ-</code>)</li>
                <li>Nomor anggota pada Digital Member Card jamaah</li>
            </ul>
        </div>
    </x-ui.card>

    <x-ui.card title="Check-in Hari Ini" subtitle="{{ $total }} orang tercatat">
        <div class="-my-2 max-h-[32rem] divide-y divide-border overflow-y-auto">
            @forelse ($today as $a)
                <div class="flex items-center gap-3 py-3">
                    <x-ui.avatar :name="$a->name ?? 'Jamaah'" size="sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium">{{ $a->name }}</p>
                        <p class="text-xs text-muted-foreground">{{ ucfirst($a->context) }} · {{ strtoupper($a->method) }}</p>
                    </div>
                    <span class="shrink-0 font-mono text-sm text-muted-foreground">{{ $a->checked_in_at->format('H:i') }}</span>
                </div>
            @empty
                <p class="py-12 text-center text-sm text-muted-foreground">Belum ada check-in hari ini.</p>
            @endforelse
        </div>
    </x-ui.card>
</div>
