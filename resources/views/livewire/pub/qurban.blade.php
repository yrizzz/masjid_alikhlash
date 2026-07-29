<div>
    <x-page-hero title="Qurban {{ $year }}" icon="beef"
                 subtitle="Daftar slot qurban, pantau progres penyembelihan, dan lihat laporan distribusi." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_20rem]">
            <div>
                <h2 class="text-lg font-bold">Pilihan Hewan Qurban</h2>

                <div class="mt-4 grid gap-5 sm:grid-cols-2">
                    @forelse ($animals as $a)
                        <div class="overflow-hidden rounded-2xl border {{ $animalId === $a->id ? 'border-primary ring-1 ring-primary' : 'border-border' }} bg-card">
                            <div class="aspect-[16/10] overflow-hidden bg-muted">
                                <img src="{{ img_url($a->photo, 'qurban'.$a->id) }}" alt="{{ $a->type }}" class="size-full object-cover" />
                            </div>
                            <div class="p-5">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold capitalize">{{ $a->type }} {{ $a->code }}</h3>
                                    <x-ui.badge :variant="$a->status === 'open' ? 'success' : ($a->status === 'full' ? 'warning' : 'muted')">
                                        {{ ['open' => 'Dibuka', 'full' => 'Penuh', 'disembelih' => 'Disembelih', 'distribusi' => 'Distribusi', 'selesai' => 'Selesai'][$a->status] ?? $a->status }}
                                    </x-ui.badge>
                                </div>

                                @if ($a->description)
                                    <p class="mt-1.5 line-clamp-2 text-sm text-muted-foreground">{{ $a->description }}</p>
                                @endif

                                <p class="mt-3 text-lg font-bold text-primary">{{ rupiah($a->price_per_slot) }}<span class="text-sm font-normal text-muted-foreground">/slot</span></p>

                                <div class="mt-3">
                                    <div class="fund-bar"><span style="width: {{ $a->progress }}%"></span></div>
                                    <p class="mt-1.5 text-xs text-muted-foreground">{{ $a->slots_taken }} dari {{ $a->slots }} slot terisi · sisa {{ $a->slots_left }}</p>
                                </div>

                                @if ($a->status === 'open' && $a->slots_left > 0)
                                    <button wire:click="choose({{ $a->id }})" class="mt-4 w-full">
                                        <x-ui.button class="w-full" :variant="$animalId === $a->id ? 'default' : 'outline'" icon="check">
                                            {{ $animalId === $a->id ? 'Dipilih' : 'Pilih Hewan Ini' }}
                                        </x-ui.button>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="sm:col-span-2">
                            <x-empty-state icon="beef" title="Belum dibuka" message="Pendaftaran qurban tahun ini belum dibuka pengurus." />
                        </div>
                    @endforelse
                </div>

                {{-- Peserta --}}
                @if ($participants->isNotEmpty())
                    <h2 class="mt-10 text-lg font-bold">Peserta Qurban {{ $year }}</h2>
                    <div class="mt-4 divide-y divide-border rounded-2xl border border-border bg-card">
                        @foreach ($participants as $p)
                            <div class="flex items-center gap-3 p-4">
                                <x-ui.avatar :name="$p->on_behalf_of ?: $p->name" size="sm" />
                                <div class="min-w-0 flex-1">
                                    <p class="truncate font-medium">{{ $p->on_behalf_of ?: $p->name }}</p>
                                    <p class="truncate text-xs text-muted-foreground">
                                        {{ ucfirst($p->animal?->type ?? '') }} · {{ $p->slots }} slot
                                    </p>
                                </div>
                                <x-ui.badge :variant="$p->status === 'lunas' ? 'success' : 'warning'">
                                    {{ $p->status === 'lunas' ? 'Lunas' : 'Menunggu' }}
                                </x-ui.badge>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Form pendaftaran --}}
            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Formulir Pendaftaran</h2>

                    @if ($created)
                        <div class="mt-4 rounded-xl border border-primary/30 bg-primary/5 p-4">
                            <p class="flex items-center gap-2 font-semibold text-primary"><i data-lucide="circle-check-big" class="size-4"></i>Terdaftar</p>
                            <p class="mt-1 text-sm text-muted-foreground">Kode: <span class="font-mono font-semibold text-foreground">{{ $created->code }}</span></p>
                            <p class="mt-2 text-lg font-bold">{{ rupiah($created->amount) }}</p>
                            <p class="mt-1 text-xs text-muted-foreground">Atas nama {{ $created->on_behalf_of }}. Silakan selesaikan pembayaran ke pengurus.</p>
                            <button wire:click="$set('created', null)" class="mt-3 text-xs text-muted-foreground hover:text-foreground">Daftar lagi</button>
                        </div>
                    @else
                        <form wire:submit="register" class="mt-4 space-y-3">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Hewan Qurban</label>
                                <select wire:model="animalId" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm">
                                    <option value="">— Pilih —</option>
                                    @foreach ($animals->where('status', 'open') as $a)
                                        <option value="{{ $a->id }}">{{ ucfirst($a->type) }} {{ $a->code }} — {{ rupiah($a->price_per_slot) }}/slot</option>
                                    @endforeach
                                </select>
                                @error('animalId')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                            </div>

                            <x-ui.input wire:model="name" label="Nama Pendaftar" :error="$errors->first('name')" />
                            <x-ui.input wire:model="onBehalfOf" label="Atas Nama (opsional)" hint="Kosongkan bila sama dengan nama pendaftar." />
                            <x-ui.input wire:model="phone" label="Nomor WhatsApp" :error="$errors->first('phone')" />
                            <x-ui.input wire:model="slotCount" type="number" label="Jumlah Slot" :error="$errors->first('slotCount')" />

                            <x-ui.button type="submit" class="w-full" icon="user-plus">Daftar Qurban</x-ui.button>
                        </form>
                    @endif
                </div>

                <div class="mt-5 rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Alur Qurban</h2>
                    <ol class="mt-3 space-y-2.5 text-sm text-muted-foreground">
                        @foreach (['Pilih hewan dan daftar slot', 'Lunasi pembayaran ke pengurus', 'Penyembelihan pada Hari Raya Idul Adha', 'Distribusi ke mustahik & jamaah', 'Laporan lengkap dengan dokumentasi foto'] as $i => $step)
                            <li class="flex gap-2.5">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-primary/10 text-[0.65rem] font-bold text-primary">{{ $i + 1 }}</span>{{ $step }}
                            </li>
                        @endforeach
                    </ol>
                </div>
            </aside>
        </div>
    </div>
</div>
