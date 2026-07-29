<div>
    <x-page-hero title="Booking Ruangan" icon="door-open"
                 subtitle="Aula, ruang rapat, ruang TPQ, dan area serbaguna dapat dipesan online." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
            <div>
                <h2 class="text-lg font-bold">Ruangan Tersedia</h2>
                <div class="mt-4 grid gap-5 sm:grid-cols-2">
                    @forelse ($rooms as $r)
                        <button wire:click="$set('roomId', {{ $r->id }})" type="button"
                                class="overflow-hidden rounded-2xl border text-start transition-all {{ $roomId === $r->id ? 'border-primary ring-1 ring-primary' : 'border-border hover:shadow-md' }} bg-card">
                            <div class="aspect-[16/9] overflow-hidden bg-muted">
                                <img src="{{ img_url($r->photo, $r->slug) }}" alt="{{ $r->name }}" class="size-full object-cover" />
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="font-semibold">{{ $r->name }}</h3>
                                    @if ($roomId === $r->id)<i data-lucide="circle-check-big" class="size-4 text-primary"></i>@endif
                                </div>
                                <p class="mt-1 line-clamp-2 text-sm text-muted-foreground">{{ $r->description }}</p>
                                <div class="mt-3 flex flex-wrap gap-3 text-xs text-muted-foreground">
                                    <span class="flex items-center gap-1"><i data-lucide="users" class="size-3.5"></i>{{ $r->capacity }} orang</span>
                                    <span class="flex items-center gap-1"><i data-lucide="tag" class="size-3.5"></i>{{ $r->fee > 0 ? rupiah($r->fee) : 'Gratis' }}</span>
                                </div>
                                @if ($r->facilities)
                                    <div class="mt-3 flex flex-wrap gap-1.5">
                                        @foreach (array_slice((array) $r->facilities, 0, 4) as $f)
                                            <span class="rounded-full bg-muted px-2 py-0.5 text-[0.7rem]">{{ $f }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </button>
                    @empty
                        <div class="sm:col-span-2">
                            <x-empty-state icon="door-open" title="Belum ada ruangan" message="Pengurus belum mendaftarkan ruangan yang bisa dipesan." />
                        </div>
                    @endforelse
                </div>

                {{-- Kalender booking --}}
                <h2 class="mt-10 text-lg font-bold">Jadwal Terpakai</h2>
                <div class="mt-4 divide-y divide-border rounded-2xl border border-border bg-card">
                    @forelse ($upcoming as $b)
                        <div class="flex items-center gap-3 p-4">
                            <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-center text-primary">
                                <div>
                                    <p class="text-[0.6rem] uppercase leading-none">{{ $b->date->translatedFormat('M') }}</p>
                                    <p class="text-sm font-bold">{{ $b->date->format('d') }}</p>
                                </div>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate font-medium">{{ $b->purpose }}</p>
                                <p class="truncate text-xs text-muted-foreground">
                                    {{ $b->room?->name }} · {{ substr($b->start_time, 0, 5) }}–{{ substr($b->end_time, 0, 5) }}
                                </p>
                            </div>
                            <x-ui.badge variant="success">Disetujui</x-ui.badge>
                        </div>
                    @empty
                        <p class="p-8 text-center text-sm text-muted-foreground">Belum ada pemesanan terjadwal.</p>
                    @endforelse
                </div>
            </div>

            {{-- Form --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Ajukan Pemesanan</h2>

                    @if ($created)
                        <div class="mt-4 rounded-xl border border-primary/30 bg-primary/5 p-4">
                            <p class="flex items-center gap-2 font-semibold text-primary"><i data-lucide="circle-check-big" class="size-4"></i>Permohonan terkirim</p>
                            <p class="mt-1 text-sm text-muted-foreground">Kode: <span class="font-mono font-semibold text-foreground">{{ $created->code }}</span></p>
                            <p class="mt-2 text-sm text-muted-foreground">
                                {{ $created->room?->name }} · {{ tanggal_id($created->date, false) }} · {{ substr($created->start_time, 0, 5) }}–{{ substr($created->end_time, 0, 5) }}
                            </p>
                            <p class="mt-2 text-xs text-muted-foreground">Takmir akan meninjau dan menghubungi Anda lewat WhatsApp.</p>
                            <button wire:click="$set('created', null)" class="mt-3 text-xs text-muted-foreground hover:text-foreground">Ajukan lagi</button>
                        </div>
                    @else
                        <form wire:submit="submit" class="mt-4 space-y-3">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Ruangan</label>
                                <select wire:model="roomId" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm">
                                    @foreach ($rooms as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                                @error('roomId')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                            </div>

                            <x-ui.input wire:model="name" label="Nama Pemohon" :error="$errors->first('name')" />
                            <x-ui.input wire:model="phone" label="Nomor WhatsApp" :error="$errors->first('phone')" />
                            <x-ui.input wire:model="purpose" label="Keperluan" hint="Contoh: Akad nikah, rapat RW, pengajian keluarga." :error="$errors->first('purpose')" />
                            <x-ui.input wire:model="date" type="date" label="Tanggal" :error="$errors->first('date')" />

                            <div class="grid grid-cols-2 gap-3">
                                <x-ui.input wire:model="startTime" type="time" label="Jam Mulai" :error="$errors->first('startTime')" />
                                <x-ui.input wire:model="endTime" type="time" label="Jam Selesai" :error="$errors->first('endTime')" />
                            </div>

                            <x-ui.input wire:model="participants" type="number" label="Perkiraan Peserta" />

                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Catatan</label>
                                <textarea wire:model="note" rows="2" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                            </div>

                            <x-ui.button type="submit" class="w-full" icon="calendar-plus">Kirim Permohonan</x-ui.button>
                        </form>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
