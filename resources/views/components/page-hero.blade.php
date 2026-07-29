@props([
    'title'    => '',
    'subtitle' => null,
    'icon'     => null,
    'compact'  => false,
])

<section class="relative -mt-16 lg:-mt-[4.75rem] overflow-hidden border-b border-amber-700/30 bg-stone-950 bg-gradient-to-br from-stone-950 via-stone-900 to-amber-950 text-white bg-islamic-pattern pt-20 sm:pt-32">
    {{-- Glow spot --}}
    <div class="pointer-events-none absolute inset-0"
         style="background: radial-gradient(50rem 35rem at 85% -20%, rgba(180, 100, 30, 0.4), transparent 70%);"></div>

    <div class="relative mx-auto max-w-7xl px-4 {{ $compact ? 'py-6 sm:py-8' : 'py-7 sm:py-14' }} sm:px-6 lg:px-8">
        <div class="flex flex-row items-center gap-3.5 sm:gap-6">
            @if ($icon)
                <span class="grid size-11 sm:size-16 shrink-0 place-items-center rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-700 via-stone-800 to-amber-950 text-amber-300 shadow-xl shadow-stone-950/40 border border-amber-500/35">
                    <i data-lucide="{{ $icon }}" class="size-5.5 sm:size-8 text-amber-300"></i>
                </span>
            @endif
            <div class="min-w-0 flex-1">
                <h1 class="text-xl sm:text-3xl md:text-4xl font-extrabold tracking-tight font-jakarta text-white drop-shadow-sm truncate">{{ $title }}</h1>
                @if ($subtitle)
                    <p class="mt-1 max-w-3xl text-xs sm:text-sm leading-relaxed text-amber-100/80 font-medium line-clamp-2 sm:line-clamp-none">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        @isset($actions)
            <div class="mt-4 sm:mt-6 flex flex-wrap items-center gap-2.5 sm:gap-3">{{ $actions }}</div>
        @endisset
    </div>
</section>
