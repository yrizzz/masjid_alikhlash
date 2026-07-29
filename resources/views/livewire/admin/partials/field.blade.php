@php
    $type = $field['type'];
    $model = 'form.'.$key;
    $error = $errors->first($model);
    $inputClass = 'flex h-10 w-full rounded-lg border bg-background px-3 py-2 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring '.($error ? 'border-destructive' : 'border-input');
@endphp

<div class="space-y-1.5">
    @if ($type !== 'toggle')
        <label class="block text-sm font-medium">
            {{ $field['label'] }}
            @if (str_contains($field['rules'] ?? '', 'required'))<span class="text-destructive">*</span>@endif
        </label>
    @endif

    @switch($type)
        @case('textarea')
            <textarea wire:model="{{ $model }}" rows="3" class="{{ $inputClass }} h-auto"></textarea>
            @break

        @case('editor')
        @case('jodit')
            <div x-data="{
                value: @entangle($model),
                instance: null,
                init() {
                    const dark = document.documentElement.classList.contains('dark');
                    if (window.Jodit) {
                        this.instance = window.Jodit.make(this.$refs.editor, {
                            theme: dark ? 'dark' : 'default',
                            height: 400,
                            placeholder: 'Tulis konten isi artikel lengkap di sini…',
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
            @break

        @case('select')
            <select wire:model="{{ $model }}" class="{{ $inputClass }}">
                <option value="">— Pilih —</option>
                @foreach ($this->optionsFor($field) as $val => $text)
                    <option value="{{ $val }}">{{ $text }}</option>
                @endforeach
            </select>
            @break

        @case('toggle')
            <label class="flex cursor-pointer items-center gap-3 py-2">
                <input type="checkbox" wire:model="{{ $model }}" class="peer sr-only" />
                <span class="relative inline-flex h-6 w-11 shrink-0 items-center rounded-full bg-muted-foreground/30 transition-colors peer-checked:bg-primary">
                    <span class="absolute start-1 inline-block size-4 rounded-full bg-white transition-transform peer-checked:translate-x-5"></span>
                </span>
                <span class="text-sm font-medium">{{ $field['label'] }}</span>
            </label>
            @break

        @case('image')
        @case('file')
            <div class="flex items-center gap-3">
                @if ($type === 'image' && ! empty($uploads[$key]))
                    <img src="{{ $uploads[$key]->temporaryUrl() }}" class="size-14 rounded-lg object-cover" />
                @elseif ($type === 'image' && ! empty($form[$key]))
                    <img src="{{ img_url($form[$key]) }}" class="size-14 rounded-lg object-cover" />
                @endif
                <input type="file" wire:model="uploads.{{ $key }}"
                       accept="{{ $type === 'image' ? 'image/*' : '' }}"
                       class="block w-full text-sm text-muted-foreground file:me-3 file:rounded-lg file:border-0 file:bg-secondary file:px-3 file:py-2 file:text-sm file:font-medium" />
            </div>
            <div wire:loading wire:target="uploads.{{ $key }}" class="text-xs text-muted-foreground">Mengunggah…</div>
            @break

        @case('money')
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 start-0 flex items-center ps-3 text-sm text-muted-foreground">Rp</span>
                <input type="number" step="any" wire:model="{{ $model }}" class="{{ $inputClass }} ps-9" />
            </div>
            @break

        @case('number')
            <input type="number" wire:model="{{ $model }}" class="{{ $inputClass }}" />
            @break

        @case('date')
            <input type="date" wire:model="{{ $model }}" class="{{ $inputClass }}" />
            @break

        @case('datetime')
            <input type="datetime-local" wire:model="{{ $model }}" class="{{ $inputClass }}" />
            @break

        @case('time')
            <input type="time" wire:model="{{ $model }}" class="{{ $inputClass }}" />
            @break

        @case('color')
            <input type="color" wire:model="{{ $model }}" class="h-10 w-full rounded-lg border border-input bg-background px-1" />
            @break

        @case('password')
            <input type="password" wire:model="{{ $model }}" autocomplete="new-password" class="{{ $inputClass }}" />
            @break

        @case('slug')
            <input type="text" wire:model="{{ $model }}" placeholder="otomatis dari {{ $field['from'] ?? 'judul' }}" class="{{ $inputClass }} font-mono text-xs" />
            @break

        @default
            <input type="text" wire:model="{{ $model }}" class="{{ $inputClass }}" />
    @endswitch

    @if ($error)
        <p class="flex items-center gap-1 text-xs font-medium text-destructive">
            <i data-lucide="circle-alert" class="size-3.5"></i>{{ $error }}
        </p>
    @elseif (! empty($field['help']))
        <p class="text-xs text-muted-foreground">{{ $field['help'] }}</p>
    @endif
</div>
