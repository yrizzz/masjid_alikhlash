{{-- Bottom navigation — mobile first, mengikuti PRD --}}
<nav class="fixed inset-x-0 bottom-0 z-40 border-t border-border bg-background/90 pb-[env(safe-area-inset-bottom)] backdrop-blur-xl lg:hidden">
    <div class="grid grid-cols-5">
        @foreach (config('masjid.bottom_nav') as $item)
            @php
                $target = $item['route'] === 'akun' && auth()->guest() ? 'login' : $item['route'];
                $active = request()->routeIs($item['route']) || request()->routeIs($item['route'].'.*');
            @endphp
            <a href="{{ route($target) }}" wire:navigate
               class="flex flex-col items-center gap-1 py-2.5 text-[0.68rem] font-medium transition-colors {{ $active ? 'text-primary' : 'text-muted-foreground' }}">
                <span class="relative">
                    <i data-lucide="{{ $item['icon'] }}" class="size-[1.35rem]"></i>
                    @if ($active)
                        <span class="absolute -top-2.5 left-1/2 h-1 w-6 -translate-x-1/2 rounded-full bg-primary"></span>
                    @endif
                </span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>
</nav>
