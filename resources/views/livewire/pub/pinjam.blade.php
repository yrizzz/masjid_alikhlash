<div>
    <x-page-hero title="Peminjaman Inventaris" icon="package"
                 subtitle="Kursi, sound system, tenda, dan perlengkapan masjid lain dapat dipinjam jamaah." />

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_22rem]">
            <div>
                <h2 class="text-lg font-bold">Barang yang Dapat Dipinjam</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($items as $i)
                        <button wire:click="$set('inventoryId', {{ $i->id }})" type="button"
                                class="overflow-hidden rounded-2xl border text-start transition-all {{ $inventoryId === $i->id ? 'border-primary ring-1 ring-primary' : 'border-border hover:shadow-md' }} bg-card">
                            <div class="aspect-[4/3] overflow-hidden bg-muted">
                                <img src="{{ img_url($i->photo, $i->code) }}" alt="{{ $i->name }}" class="size-full object-cover" />
                            </div>
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="font-medium leading-snug">{{ $i->name }}</h3>
                                    @if ($inventoryId === $i->id)<i data-lucide="circle-check-big" class="size-4 shrink-0 text-primary"></i>@endif
                                </div>
                                <p class="mt-1 text-xs text-muted-foreground">{{ $i->category?->name }}</p>
                                <div class="mt-2 flex items-center justify-between text-xs">
                                    <span class="text-muted-foreground">Stok {{ $i->quantity }} {{ $i->unit }}</span>
                                    <x-ui.badge :variant="$i->condition === 'baik' ? 'success' : 'warning'">
                                        {{ \App\Models\Inventory::CONDITIONS[$i->condition] ?? $i->condition }}
                                    </x-ui.badge>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="sm:col-span-2 lg:col-span-3">
                            <x-empty-state icon="package" title="Belum ada barang" message="Pengurus belum menandai inventaris yang boleh dipinjam." />
                        </div>
                    @endforelse
                </div>

                <div class="mt-8 rounded-2xl border border-border bg-card p-6">
                    <h2 class="font-bold">Ketentuan Peminjaman</h2>
                    <ul class="mt-3 space-y-2 text-sm leading-relaxed text-muted-foreground">
                        <li class="flex gap-2"><i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-primary"></i>Peminjaman diajukan minimal H-1 dan menunggu persetujuan takmir.</li>
                        <li class="flex gap-2"><i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-primary"></i>Barang dikembalikan dalam keadaan bersih dan lengkap sesuai tanggal.</li>
                        <li class="flex gap-2"><i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-primary"></i>Kerusakan atau kehilangan menjadi tanggung jawab peminjam.</li>
                        <li class="flex gap-2"><i data-lucide="check" class="mt-0.5 size-4 shrink-0 text-primary"></i>Prioritas diberikan untuk kegiatan masjid dan warga sekitar.</li>
                    </ul>
                </div>
            </div>

            <aside class="lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-2xl border border-border bg-card p-5">
                    <h2 class="font-semibold">Formulir Peminjaman</h2>

                    @if ($created)
                        <div class="mt-4 rounded-xl border border-primary/30 bg-primary/5 p-4">
                            <p class="flex items-center gap-2 font-semibold text-primary"><i data-lucide="circle-check-big" class="size-4"></i>Permohonan terkirim</p>
                            <p class="mt-1 text-sm text-muted-foreground">Kode: <span class="font-mono font-semibold text-foreground">{{ $created->code }}</span></p>
                            <p class="mt-2 text-xs text-muted-foreground">Takmir akan menghubungi Anda untuk konfirmasi pengambilan.</p>
                            <button wire:click="$set('created', null)" class="mt-3 text-xs text-muted-foreground hover:text-foreground">Ajukan lagi</button>
                        </div>
                    @else
                        <form wire:submit="submit" class="mt-4 space-y-3">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Barang</label>
                                <select wire:model="inventoryId" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm">
                                    <option value="">— Pilih —</option>
                                    @foreach ($items as $i)
                                        <option value="{{ $i->id }}">{{ $i->name }} (stok {{ $i->quantity }})</option>
                                    @endforeach
                                </select>
                                @error('inventoryId')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                            </div>

                            <x-ui.input wire:model="borrower" label="Nama Peminjam" :error="$errors->first('borrower')" />
                            <x-ui.input wire:model="phone" label="Nomor WhatsApp" :error="$errors->first('phone')" />
                            <x-ui.input wire:model="quantity" type="number" label="Jumlah" :error="$errors->first('quantity')" />

                            <div class="grid grid-cols-2 gap-3">
                                <x-ui.input wire:model="borrowDate" type="date" label="Tanggal Pinjam" :error="$errors->first('borrowDate')" />
                                <x-ui.input wire:model="dueDate" type="date" label="Rencana Kembali" :error="$errors->first('dueDate')" />
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium">Keperluan</label>
                                <textarea wire:model="purpose" rows="3" class="w-full rounded-lg border {{ $errors->has('purpose') ? 'border-destructive' : 'border-input' }} bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                                @error('purpose')<p class="text-xs text-destructive">{{ $message }}</p>@enderror
                            </div>

                            <x-ui.button type="submit" class="w-full" icon="send">Kirim Permohonan</x-ui.button>
                        </form>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
