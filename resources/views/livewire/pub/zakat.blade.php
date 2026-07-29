<div>
    <x-page-hero title="Kalkulator Zakat" icon="coins"
                 subtitle="Hitung zakat fitrah, maal, profesi, emas, dan perdagangan — lalu tunaikan langsung lewat masjid." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
            <div>
                {{-- Tab jenis zakat --}}
                <div class="flex flex-wrap gap-1.5">
                    @foreach ($types as $key => $label)
                        <button wire:click="$set('type', '{{ $key }}')"
                                class="rounded-xl px-4 py-2.5 text-sm font-medium transition-colors {{ $type === $key ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground hover:text-foreground' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-6 rounded-2xl border border-border bg-card p-6">
                    @php
                        $fields = match ($type) {
                            'fitrah'      => [['people', 'Jumlah Jiwa yang Dizakati', 'number', 'Termasuk diri sendiri dan tanggungan.']],
                            'maal'        => [['assets', 'Total Harta Simpanan', 'money', 'Tabungan, deposito, emas, dll yang mengendap 1 tahun.'], ['debts', 'Hutang Jatuh Tempo', 'money', null]],
                            'profesi'     => [['income', 'Penghasilan per Bulan', 'money', null], ['other', 'Penghasilan Lain per Bulan', 'money', null], ['needs', 'Kebutuhan Pokok per Bulan', 'money', 'Boleh dikosongkan bila menghitung dari penghasilan bruto.']],
                            'emas'        => [['gold', 'Emas (gram)', 'number', 'Nisab 85 gram.'], ['silver', 'Perak (gram)', 'number', 'Nisab 595 gram.']],
                            'perdagangan' => [['capital', 'Modal Usaha', 'money', null], ['profit', 'Laba', 'money', null], ['receivable', 'Piutang Lancar', 'money', null], ['debts', 'Hutang Usaha', 'money', null]],
                            default       => [],
                        };
                    @endphp

                    <h2 class="font-bold">{{ $types[$type] }}</h2>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach ($fields as [$key, $label, $kind, $help])
                            <div class="space-y-1.5 {{ count($fields) === 1 ? 'sm:col-span-2' : '' }}">
                                <label class="block text-sm font-medium">{{ $label }}</label>
                                <div class="relative">
                                    @if ($kind === 'money')
                                        <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-sm text-muted-foreground">Rp</span>
                                    @endif
                                    <input type="number" step="any" wire:model.live.debounce.500ms="input.{{ $key }}"
                                           class="h-11 w-full rounded-lg border border-input bg-background {{ $kind === 'money' ? 'ps-9' : 'ps-3' }} pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                                </div>
                                @if ($help)<p class="text-xs text-muted-foreground">{{ $help }}</p>@endif
                            </div>
                        @endforeach
                    </div>

                    <button wire:click="calculate" class="mt-5">
                        <x-ui.button icon="calculator">Hitung Zakat</x-ui.button>
                    </button>

                    {{-- Hasil --}}
                    @if ($result)
                        <div class="mt-6 rounded-2xl border border-primary/25 bg-primary/5 p-5">
                            <p class="text-sm text-muted-foreground">Zakat yang perlu ditunaikan</p>
                            <p class="mt-1 text-3xl font-bold text-primary">{{ rupiah($result['amount'] ?? 0) }}</p>

                            @if ($type === 'fitrah')
                                <p class="mt-2 text-sm text-muted-foreground">
                                    Setara {{ number_format($result['kg_total'] ?? 0, 1, ',', '.') }} kg beras
                                    ({{ rupiah($result['per_jiwa'] ?? 0) }} × {{ $result['people'] ?? 1 }} jiwa).
                                </p>
                            @else
                                <p class="mt-2 text-sm {{ ($result['wajib'] ?? false) ? 'text-foreground' : 'text-muted-foreground' }}">{{ $result['note'] ?? '' }}</p>
                                @if (! empty($result['base']))
                                    <p class="mt-1 text-xs text-muted-foreground">Dasar perhitungan: {{ rupiah($result['base']) }}</p>
                                @endif
                            @endif

                            @if (($result['amount'] ?? 0) > 0)
                                @if ($created)
                                    <div class="mt-4 rounded-xl bg-background p-4">
                                        <p class="flex items-center gap-2 text-sm font-semibold text-primary">
                                            <i data-lucide="circle-check-big" class="size-4"></i>Tercatat — kode {{ $created->code }}
                                        </p>
                                        <p class="mt-1.5 text-sm text-muted-foreground">Silakan transfer ke rekening masjid lalu konfirmasi ke pengurus.</p>
                                    </div>
                                @elseif ($showPay)
                                    <form wire:submit="pay" class="mt-4 space-y-3 rounded-xl bg-background p-4">
                                        <x-ui.input wire:model="name" label="Nama Muzakki" :error="$errors->first('name')" />
                                        <x-ui.input wire:model="phone" label="Nomor WhatsApp" :error="$errors->first('phone')" />
                                        <div class="flex gap-2">
                                            <x-ui.button type="submit" icon="check">Konfirmasi</x-ui.button>
                                            <x-ui.button type="button" variant="outline" wire:click="$set('showPay', false)">Batal</x-ui.button>
                                        </div>
                                    </form>
                                @else
                                    <button wire:click="$set('showPay', true)" class="mt-4">
                                        <x-ui.button icon="hand-heart">Tunaikan Lewat Masjid</x-ui.button>
                                    </button>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Penjelasan --}}
                <div class="mt-6 rounded-2xl border border-border bg-card p-6">
                    <h2 class="font-bold">Catatan Perhitungan</h2>
                    <ul class="mt-3 space-y-2 text-sm leading-relaxed text-muted-foreground">
                        <li class="flex gap-2"><i data-lucide="dot" class="mt-0.5 size-4 shrink-0 text-primary"></i>Nisab zakat maal setara 85 gram emas, dengan kadar zakat 2,5% dan haul satu tahun.</li>
                        <li class="flex gap-2"><i data-lucide="dot" class="mt-0.5 size-4 shrink-0 text-primary"></i>Zakat profesi dihitung bulanan dengan nisab 1/12 dari nisab tahunan.</li>
                        <li class="flex gap-2"><i data-lucide="dot" class="mt-0.5 size-4 shrink-0 text-primary"></i>Zakat fitrah setara 2,5 kg makanan pokok per jiwa, ditunaikan sebelum sholat Id.</li>
                        <li class="flex gap-2"><i data-lucide="dot" class="mt-0.5 size-4 shrink-0 text-primary"></i>Harga acuan diperbarui pengurus dan dapat berbeda dengan harga pasar terkini.</li>
                    </ul>
                </div>
            </div>

            <aside class="space-y-5">
                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Acuan Saat Ini</h2>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between gap-3"><dt class="text-muted-foreground">Harga emas/gram</dt><dd class="font-semibold">{{ rupiah($goldPrice) }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-muted-foreground">Nisab (85 gr)</dt><dd class="font-semibold">{{ rupiah($nisab) }}</dd></div>
                        <div class="flex justify-between gap-3"><dt class="text-muted-foreground">Harga beras/kg</dt><dd class="font-semibold">{{ rupiah($ricePrice) }}</dd></div>
                    </dl>
                </div>

                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Kanal Pembayaran</h2>
                    <div class="mt-4 space-y-3">
                        @forelse ($channels as $c)
                            <div class="rounded-xl border border-border p-3">
                                <p class="text-sm font-medium">{{ $c->name }}</p>
                                @if ($c->account_number)
                                    <p class="font-mono text-sm">{{ $c->account_number }}</p>
                                    <p class="text-xs text-muted-foreground">a.n. {{ $c->account_name }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">Belum ada kanal pembayaran.</p>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
