<div class="space-y-5">
    <x-ui.card title="Unggah Berkas" subtitle="Total terpakai: {{ round($used / 1048576, 1) }} MB">
        <form wire:submit="upload" class="flex flex-wrap items-end gap-3">
            <div class="min-w-[14rem] flex-1 space-y-1.5">
                <label class="block text-sm font-medium">Pilih berkas (maks 10 MB)</label>
                <input type="file" wire:model="files" multiple
                       class="block w-full text-sm text-muted-foreground file:me-3 file:rounded-lg file:border-0 file:bg-secondary file:px-3 file:py-2 file:text-sm file:font-medium" />
            </div>
            <div class="space-y-1.5">
                <label class="block text-sm font-medium">Folder</label>
                <input type="text" wire:model="folder"
                       class="h-10 w-40 rounded-lg border border-input bg-background px-3 text-sm" />
            </div>
            <x-ui.button type="submit" icon="upload">
                <span wire:loading.remove wire:target="upload,files">Unggah</span>
                <span wire:loading wire:target="upload,files">Memproses…</span>
            </x-ui.button>
        </form>
    </x-ui.card>

    <x-ui.card title="Pustaka Media" subtitle="{{ $items->total() }} berkas">
        <x-slot:actions>
            <input wire:model.live.debounce.400ms="search" type="search" placeholder="Cari berkas…"
                   class="h-9 rounded-lg border border-input bg-background px-3 text-sm" />
        </x-slot:actions>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @forelse ($items as $m)
                <div wire:key="m-{{ $m->id }}" class="group relative overflow-hidden rounded-xl border border-border">
                    <div class="aspect-square bg-muted">
                        @if (str_starts_with((string) $m->mime, 'image/'))
                            <img src="{{ $m->url }}" alt="{{ $m->alt }}" class="size-full object-cover" />
                        @else
                            <div class="grid size-full place-items-center text-muted-foreground">
                                <i data-lucide="file" class="size-8"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-2">
                        <p class="truncate text-xs font-medium">{{ $m->name }}</p>
                        <p class="text-[0.7rem] text-muted-foreground">{{ $m->human_size }}</p>
                    </div>
                    <div class="absolute inset-x-0 top-0 flex justify-end gap-1 p-1.5 opacity-0 transition-opacity group-hover:opacity-100">
                        <a href="{{ $m->url }}" target="_blank" class="rounded-lg bg-background/90 p-1.5 shadow-sm hover:bg-background">
                            <i data-lucide="external-link" class="size-3.5"></i>
                        </a>
                        <button wire:click="delete({{ $m->id }})" wire:confirm="Hapus berkas ini?"
                                class="rounded-lg bg-background/90 p-1.5 text-destructive shadow-sm hover:bg-background">
                            <i data-lucide="trash-2" class="size-3.5"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <i data-lucide="folder-open" class="mx-auto size-10 text-muted-foreground/40"></i>
                    <p class="mt-3 text-sm text-muted-foreground">Belum ada berkas.</p>
                </div>
            @endforelse
        </div>

        @if ($items->hasPages())
            <div class="mt-5">{{ $items->links() }}</div>
        @endif
    </x-ui.card>
</div>
