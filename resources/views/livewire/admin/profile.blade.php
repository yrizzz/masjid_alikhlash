<div class="space-y-5">
    <form wire:submit="save" class="space-y-5">
        <x-ui.card title="Identitas & Lokasi" subtitle="Data ini dipakai di seluruh halaman publik, footer, dan perhitungan waktu sholat.">
            <x-slot:actions>
                <x-ui.button type="submit" icon="save">Simpan</x-ui.button>
            </x-slot:actions>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($fields as $key => [$label, $type])
                    <div class="{{ in_array($type, ['textarea', 'editor'], true) ? 'sm:col-span-2' : '' }} space-y-1.5">
                        <label class="block text-sm font-medium">{{ $label }}</label>
                        @if ($type === 'textarea')
                            <textarea wire:model="form.{{ $key }}" rows="3"
                                      class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                        @elseif ($type === 'editor')
                            <div x-data="{
                                value: @entangle('form.'.$key),
                                instance: null,
                                init() {
                                    const dark = document.documentElement.classList.contains('dark');
                                    if (window.Jodit) {
                                        this.instance = window.Jodit.make(this.$refs.editor, {
                                            theme: dark ? 'dark' : 'default',
                                            height: 380,
                                            placeholder: 'Tulis konten lengkap di sini…',
                                            buttons: [
                                                'bold', 'italic', 'underline', 'strikethrough', '|',
                                                'font', 'fontsize', 'brush', 'paragraph', '|',
                                                'image', 'table', 'link', '|',
                                                'align', 'undo', 'redo', '|',
                                                'hr', 'eraser', 'fullsize', 'source'
                                            ]
                                        });
                                        this.instance.value = this.value || '';
                                        this.instance.events.on('change', (newVal) => {
                                            this.value = newVal;
                                        });
                                        this.$watch('value', (val) => {
                                            if (this.instance && this.instance.value !== val) {
                                                this.instance.value = val || '';
                                            }
                                        });
                                    }
                                }
                            }" wire:ignore class="rounded-xl border border-input overflow-hidden bg-background">
                                <textarea x-ref="editor" class="w-full"></textarea>
                            </div>
                        @else
                            <input type="text" wire:model="form.{{ $key }}"
                                   class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                        @endif
                    </div>
                @endforeach
            </div>
        </x-ui.card>
    </form>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <x-ui.card title="Timeline Perkembangan" subtitle="{{ $milestones->count() }} tonggak sejarah tercatat">
            <x-slot:actions>
                <x-ui.button type="button" wire:click="openMilestoneModal()" size="sm" icon="plus">Tambah Timeline</x-ui.button>
            </x-slot:actions>
            <div class="space-y-4">
                @forelse ($milestones as $m)
                    <div class="group flex gap-3 items-start justify-between">
                        <div class="flex gap-3">
                            <div class="flex flex-col items-center">
                                <span class="grid size-9 shrink-0 place-items-center rounded-full bg-primary/10 text-primary">
                                    <i data-lucide="{{ $m->icon ?? 'sparkles' }}" class="size-4"></i>
                                </span>
                                @if (! $loop->last)<span class="mt-1 w-px flex-1 bg-border"></span>@endif
                            </div>
                            <div class="pb-2">
                                <p class="text-sm font-semibold">{{ $m->year }} — {{ $m->title }}</p>
                                <p class="text-sm text-muted-foreground">{{ $m->description }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition-opacity">
                            <button type="button" wire:click="openMilestoneModal({{ $m->id }})" class="p-1 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded dark:hover:bg-amber-950/40">
                                <i data-lucide="pencil" class="size-4"></i>
                            </button>
                            <button type="button" wire:click="deleteMilestone({{ $m->id }})" wire:confirm="Hapus tonggak sejarah ini?" class="p-1 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded dark:hover:bg-rose-950/40">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="py-6 text-center text-sm text-muted-foreground">Belum ada data timeline.</p>
                @endforelse
            </div>
        </x-ui.card>

        <x-ui.card title="Fasilitas Masjid" subtitle="{{ $facilities->count() }} fasilitas terdaftar">
            <x-slot:actions>
                <x-ui.button type="button" wire:click="openFacilityModal()" size="sm" icon="plus">Tambah Fasilitas</x-ui.button>
            </x-slot:actions>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse ($facilities as $f)
                    <div class="group flex items-center justify-between gap-2.5 rounded-xl border border-border p-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <i data-lucide="{{ $f->icon ?? 'building-2' }}" class="size-4 shrink-0 text-primary"></i>
                            <span class="truncate text-sm font-medium">{{ $f->name }}</span>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            <button type="button" wire:click="openFacilityModal({{ $f->id }})" class="p-1 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded dark:hover:bg-amber-950/40">
                                <i data-lucide="pencil" class="size-3.5"></i>
                            </button>
                            <button type="button" wire:click="deleteFacility({{ $f->id }})" wire:confirm="Hapus fasilitas ini?" class="p-1 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded dark:hover:bg-rose-950/40">
                                <i data-lucide="trash-2" class="size-3.5"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="col-span-2 py-6 text-center text-sm text-muted-foreground">Belum ada fasilitas.</p>
                @endforelse
            </div>
        </x-ui.card>
    </div>

    <!-- Modal Milestone -->
    <x-ui.modal name="milestone-modal" :show="$showMilestoneModal" focusable>
        <div class="p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-border">
                <h3 class="text-lg font-semibold">{{ $milestoneForm['id'] ? 'Ubah' : 'Tambah' }} Timeline Perkembangan</h3>
                <button type="button" wire:click="$set('showMilestoneModal', false)" class="rounded-xl p-2 text-stone-500 hover:bg-stone-100 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-white transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form wire:submit="saveMilestone" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium">Tahun</label>
                        <input type="text" wire:model="milestoneForm.year" placeholder="1978" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium">Ikon (Lucide)</label>
                        <input type="text" wire:model="milestoneForm.icon" placeholder="sparkles" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium">Judul Peristiwa</label>
                    <input type="text" wire:model="milestoneForm.title" placeholder="Pendirian Mushola Al-Ikhlash" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium">Keterangan / Deskripsi</label>
                    <textarea wire:model="milestoneForm.description" rows="3" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-border">
                    <x-ui.button type="button" variant="outline" wire:click="$set('showMilestoneModal', false)">Batal</x-ui.button>
                    <x-ui.button type="submit" icon="save">Simpan</x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.modal>

    <!-- Modal Facility -->
    <x-ui.modal name="facility-modal" :show="$showFacilityModal" focusable>
        <div class="p-6">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-border">
                <h3 class="text-lg font-semibold">{{ $facilityForm['id'] ? 'Ubah' : 'Tambah' }} Fasilitas Masjid</h3>
                <button type="button" wire:click="$set('showFacilityModal', false)" class="rounded-xl p-2 text-stone-500 hover:bg-stone-100 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-white transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form wire:submit="saveFacility" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium">Nama Fasilitas</label>
                    <input type="text" wire:model="facilityForm.name" placeholder="Ruang Utama 2 Lantai" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium">Ikon (Lucide)</label>
                        <input type="text" wire:model="facilityForm.icon" placeholder="building-2" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium">Urutan</label>
                        <input type="number" wire:model="facilityForm.order" class="h-10 w-full rounded-lg border border-input bg-background px-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium">Keterangan / Deskripsi</label>
                    <textarea wire:model="facilityForm.description" rows="3" class="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t border-border">
                    <x-ui.button type="button" variant="outline" wire:click="$set('showFacilityModal', false)">Batal</x-ui.button>
                    <x-ui.button type="submit" icon="save">Simpan</x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.modal>
</div>
