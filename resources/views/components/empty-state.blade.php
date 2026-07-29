@props([
    'icon'    => 'inbox',
    'title'   => 'Belum ada data',
    'message' => null,
])

<div {{ $attributes->class('rounded-2xl border border-dashed border-border px-6 py-16 text-center') }}>
    <i data-lucide="{{ $icon }}" class="mx-auto size-10 text-muted-foreground/40"></i>
    <p class="mt-4 font-semibold">{{ $title }}</p>
    @if ($message)
        <p class="mt-1 text-sm text-muted-foreground">{{ $message }}</p>
    @endif
    {{ $slot }}
</div>
