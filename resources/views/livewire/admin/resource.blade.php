@php
    $cols = $def['columns'];
    $creatable = $def['creatable'] ?? true;
@endphp

<div class="space-y-5">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary/10 text-primary">
                <i data-lucide="{{ $def['icon'] }}" class="size-5"></i>
            </span>
            <div>
                <h1 class="text-xl font-bold tracking-tight">{{ $def['title'] }}</h1>
                <p class="text-sm text-muted-foreground">{{ $rows->total() }} data tersimpan</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <div class="relative">
                <i data-lucide="search" class="pointer-events-none absolute inset-y-0 start-0 my-auto ms-3 size-4 text-muted-foreground"></i>
                <input wire:model.live.debounce.400ms="search" type="search" placeholder="Cari…"
                       class="h-10 w-full min-w-[12rem] rounded-lg border border-input bg-background ps-9 pe-3 text-sm focus:outline-none focus:ring-2 focus:ring-ring" />
            </div>
            @if ($creatable)
                <x-ui.button wire:click="create" icon="plus">Tambah</x-ui.button>
            @endif
        </div>
    </div>

    {{-- Tabel --}}
    <div class="ak-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-border bg-muted/40 text-xs uppercase tracking-wide text-muted-foreground">
                    <tr>
                        @foreach ($cols as $key => $col)
                            <th class="whitespace-nowrap px-4 py-3 text-start font-semibold">
                                @if (! str_contains($key, '.'))
                                    <button wire:click="sort('{{ $key }}')" class="inline-flex items-center gap-1 hover:text-foreground">
                                        {{ $col['label'] }}
                                        @if ($sortBy === $key)
                                            <i data-lucide="{{ $sortDir === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-3"></i>
                                        @endif
                                    </button>
                                @else
                                    {{ $col['label'] }}
                                @endif
                            </th>
                        @endforeach
                        <th class="px-4 py-3 text-end font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($rows as $row)
                        <tr wire:key="row-{{ $row->id }}" class="transition-colors hover:bg-muted/40">
                            @foreach ($cols as $key => $col)
                                <td class="px-4 py-3 align-middle">
                                    @include('livewire.admin.partials.cell', ['row' => $row, 'key' => $key, 'col' => $col])
                                </td>
                            @endforeach
                            <td class="px-4 py-3 text-end">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="edit({{ $row->id }})" title="Ubah"
                                            class="rounded-lg p-2 text-muted-foreground transition-colors hover:bg-accent hover:text-foreground">
                                        <i data-lucide="pencil" class="size-4"></i>
                                    </button>
                                    @if ($confirmingDelete === $row->id)
                                        <button wire:click="delete({{ $row->id }})"
                                                class="rounded-lg bg-destructive px-2.5 py-1.5 text-xs font-semibold text-destructive-foreground">Yakin?</button>
                                        <button wire:click="$set('confirmingDelete', null)"
                                                class="rounded-lg p-2 text-muted-foreground hover:bg-accent"><i data-lucide="x" class="size-4"></i></button>
                                    @else
                                        <button wire:click="$set('confirmingDelete', {{ $row->id }})" title="Hapus"
                                                class="rounded-lg p-2 text-muted-foreground transition-colors hover:bg-destructive/10 hover:text-destructive">
                                            <i data-lucide="trash-2" class="size-4"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($cols) + 1 }}" class="px-4 py-16 text-center">
                                <i data-lucide="inbox" class="mx-auto size-10 text-muted-foreground/40"></i>
                                <p class="mt-3 font-medium">Belum ada data</p>
                                <p class="text-sm text-muted-foreground">
                                    {{ $search ? 'Tidak ada hasil untuk pencarian "'.$search.'".' : 'Tambahkan data pertama Anda.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($rows->hasPages())
            <div class="border-t border-border px-4 py-3">{{ $rows->links() }}</div>
        @endif
    </div>

    {{-- Form modal --}}
    @if ($showForm)
        <div class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto p-4 sm:p-6">
            <div class="fixed inset-0 bg-background/70 backdrop-blur-sm" wire:click="$set('showForm', false)"></div>

            <div class="relative my-4 w-full max-w-3xl rounded-2xl border border-border bg-card shadow-2xl">
                <div class="flex items-center justify-between border-b border-border px-6 py-4">
                    <h3 class="text-lg font-semibold">
                        {{ $editingId ? 'Ubah' : 'Tambah' }} {{ $def['singular'] }}
                    </h3>
                    <button type="button" wire:click="$set('showForm', false)"
                            class="rounded-xl p-2 text-stone-500 hover:bg-stone-100 hover:text-stone-900 dark:text-stone-400 dark:hover:bg-stone-800 dark:hover:text-white transition-colors cursor-pointer"
                            aria-label="Tutup">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="grid max-h-[65vh] grid-cols-1 gap-4 overflow-y-auto px-6 py-5 sm:grid-cols-2">
                        @foreach ($def['fields'] as $key => $field)
                            <div class="{{ ($field['col'] ?? 1) === 2 ? 'sm:col-span-2' : '' }}">
                                @include('livewire.admin.partials.field', ['key' => $key, 'field' => $field])
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-border bg-muted/30 px-6 py-4">
                        <x-ui.button type="button" variant="outline" wire:click="$set('showForm', false)">Batal</x-ui.button>
                        <x-ui.button type="submit" icon="save">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">Menyimpan…</span>
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
