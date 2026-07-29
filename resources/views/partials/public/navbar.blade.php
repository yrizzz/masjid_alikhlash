@php
    $nav = config('masjid.nav');
    $siteName = setting('name', config('masjid.name'));
    $isHome = request()->routeIs('home');
@endphp

<header x-data="{ open: false, scrolled: false, isHome: {{ $isHome ? 'true' : 'false' }}, mega: null }"
        @scroll.window="scrolled = window.scrollY > 20"
        class="fixed inset-x-0 top-0 z-50 transition-all duration-300"
        :class="(scrolled || open || !isHome)
            ? 'bg-background/95 backdrop-blur-2xl border-b border-stone-200/80 dark:border-stone-800/80 shadow-md shadow-stone-950/5'
            : 'bg-transparent border-transparent shadow-none'">

    <nav class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-2 px-3.5 sm:px-6 lg:h-[4.75rem] lg:px-8">
        {{-- Brand --}}
        <a href="{{ route('home') }}" wire:navigate class="flex shrink min-w-0 items-center gap-2 sm:gap-3 group max-w-[65%] sm:max-w-none">
            <span class="relative grid size-9 sm:size-10.5 shrink-0 place-items-center rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-700 via-stone-800 to-amber-900 text-amber-300 shadow-md shadow-stone-900/30 transition-transform duration-300 group-hover:scale-105 border border-amber-500/30">
                <i data-lucide="moon-star" class="size-4.5 sm:size-5.5 text-amber-300"></i>
                <span class="absolute -bottom-0.5 -right-0.5 size-2.5 sm:size-3 rounded-full bg-amber-400 border-2 border-background"></span>
            </span>
            <span class="block min-w-0 flex-1">
                <span class="block text-xs sm:text-[1.05rem] font-bold leading-tight tracking-tight font-jakarta transition-colors truncate"
                      :class="(scrolled || open || !isHome) ? 'text-foreground' : 'text-white'">{{ $siteName }}</span>
                <span class="block text-[0.65rem] sm:text-[0.7rem] font-medium leading-tight transition-colors truncate hidden xs:block"
                      :class="(!scrolled && !open && isHome) ? 'text-amber-200/90' : 'text-amber-700 dark:text-amber-400'">Pusat Ibadah & Umat · {{ config('masjid.village') }}</span>
            </span>
        </a>

        {{-- Menu desktop --}}
        <div class="mx-auto hidden items-center gap-1 lg:flex">
            @foreach ($nav as $item)
                @if (empty($item['children']))
                    @php $isActive = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}" wire:navigate
                       class="relative rounded-xl px-3.5 py-2 text-sm font-semibold transition-all duration-200 {{ $isActive ? 'text-amber-600 dark:text-amber-400 bg-amber-500/15 border border-amber-500/30 font-bold' : 'text-stone-700 dark:text-stone-200 hover:text-amber-700 dark:hover:text-amber-400 hover:bg-amber-500/10' }}"
                       :class="(!scrolled && !open && isHome) ? ('{{ $isActive }}' ? '!text-amber-300 !bg-amber-500/25 !border-amber-500/40 font-bold' : '!text-stone-100 hover:!text-white hover:!bg-amber-500/15') : ''">
                        {{ $item['label'] }}
                    </a>
                @else
                    <div class="relative" @mouseenter="mega = '{{ $item['label'] }}'" @mouseleave="mega = null">
                        <button class="flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-sm font-semibold text-stone-700 dark:text-stone-200 transition-all hover:bg-amber-500/10 hover:text-amber-700 dark:hover:text-amber-400"
                                :class="(!scrolled && !open && isHome) && '!text-stone-100 hover:!text-white hover:!bg-amber-500/15'">
                            {{ $item['label'] }}
                            <i data-lucide="chevron-down" class="size-3.5 transition-transform duration-200" :class="mega === '{{ $item['label'] }}' && 'rotate-180'"></i>
                        </button>

                        <div x-show="mega === '{{ $item['label'] }}'" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             class="absolute start-1/2 top-full z-50 w-[27rem] -translate-x-1/2 pt-2">
                            <div class="grid grid-cols-1 gap-1.5 rounded-2xl border border-amber-700/25 bg-background/98 p-2.5 shadow-2xl backdrop-blur-2xl">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ route($child['route']) }}" wire:navigate
                                       class="group/item flex items-start gap-3.5 rounded-xl p-3 transition-all hover:bg-amber-500/10">
                                        <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-amber-600/15 to-stone-500/20 text-amber-700 dark:text-amber-400 border border-amber-600/20 group-hover/item:bg-amber-700 group-hover/item:text-white transition-colors">
                                            <i data-lucide="{{ $child['icon'] }}" class="size-5"></i>
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block text-sm font-bold text-foreground group-hover/item:text-amber-700 dark:group-hover/item:text-amber-400">{{ $child['label'] }}</span>
                                            <span class="block text-xs text-muted-foreground leading-snug">{{ $child['desc'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Aksi & Mobile Menu Toggle --}}
        <div class="flex items-center gap-1 sm:gap-2 shrink-0">
            <a href="{{ route('search') }}" wire:navigate
               class="rounded-xl p-2 text-stone-600 dark:text-stone-300 transition-all hover:bg-amber-500/10 hover:text-amber-700 dark:hover:text-amber-400"
               :class="(!scrolled && !open && isHome) && '!text-white/90 hover:!text-white hover:!bg-white/10'" title="Cari Informasi">
                <i data-lucide="search" class="size-5"></i>
            </a>
            <button type="button" @click="$store.ui.toggleTheme()" title="Ganti tema"
                    class="rounded-xl p-2 text-stone-600 dark:text-stone-300 transition-all hover:bg-amber-500/10 hover:text-amber-700 dark:hover:text-amber-400"
                    :class="(!scrolled && !open && isHome) && '!text-white/90 hover:!text-white hover:!bg-white/10'">
                <i data-lucide="sun" class="size-5 text-amber-400" x-show="$store.ui.isDark" x-cloak></i>
                <i data-lucide="moon" class="size-5" x-show="!$store.ui.isDark"></i>
            </button>

            @auth
                <a href="{{ auth()->user()->isStaff() ? route('admin.dashboard') : route('akun') }}" wire:navigate
                   class="hidden lg:block">
                    <x-ui.avatar :name="auth()->user()->name" size="sm" />
                </a>
            @else
                <a href="{{ route('login') }}" wire:navigate class="hidden lg:block">
                    <x-ui.button size="sm" variant="outline" class="border-amber-500/30 hover:bg-amber-500/10">Masuk</x-ui.button>
                </a>
            @endauth

            <a href="{{ route('donasi') }}" wire:navigate class="hidden sm:block">
                <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-600 via-amber-700 to-amber-800 px-3.5 py-1.5 sm:px-4 sm:py-2 text-xs font-bold text-white shadow-md shadow-amber-950/20 transition-all hover:scale-105 hover:shadow-lg hover:shadow-amber-600/30 border border-amber-400/40">
                    <i data-lucide="hand-heart" class="size-4 text-amber-200"></i> Donasi Umat
                </button>
            </a>

            {{-- Hamburger Button --}}
            <button type="button" @click="open = !open"
                    class="rounded-xl p-2 text-foreground lg:hidden shrink-0 border border-stone-200/80 dark:border-stone-800/80 bg-stone-100/50 dark:bg-stone-900/50 backdrop-blur-sm transition-colors"
                    :class="(!scrolled && !open && isHome) && '!text-white !border-white/20 !bg-white/10'">
                <i data-lucide="menu" class="size-5.5" x-show="!open"></i>
                <i data-lucide="x" class="size-5.5 text-amber-500" x-show="open" x-cloak></i>
            </button>
        </div>
    </nav>

    {{-- Menu mobile drawer --}}
    <div x-show="open" x-cloak x-collapse class="max-h-[calc(100vh-4rem)] overflow-y-auto border-t border-border bg-background lg:hidden">
        <div class="space-y-1 px-4 py-4">
            @foreach ($nav as $item)
                @if (empty($item['children']))
                    <a href="{{ route($item['route']) }}" wire:navigate @click="open = false"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium hover:bg-amber-500/10 hover:text-amber-600 dark:hover:text-amber-400">
                        <i data-lucide="{{ $item['icon'] }}" class="size-[1.1rem] text-amber-600 dark:text-amber-400"></i>{{ $item['label'] }}
                    </a>
                @else
                    <div x-data="{ sub: false }">
                        <button @click="sub = !sub" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium hover:bg-amber-500/10 hover:text-amber-600 dark:hover:text-amber-400">
                            <i data-lucide="{{ $item['icon'] }}" class="size-[1.1rem] text-amber-600 dark:text-amber-400"></i>
                            <span class="flex-1 text-start">{{ $item['label'] }}</span>
                            <i data-lucide="chevron-down" class="size-4 transition-transform" :class="sub && 'rotate-180'"></i>
                        </button>
                        <div x-show="sub" x-collapse x-cloak class="ms-4 space-y-0.5 border-s border-border ps-3">
                            @foreach ($item['children'] as $child)
                                <a href="{{ route($child['route']) }}" wire:navigate @click="open = false"
                                   class="block rounded-lg px-3 py-2 text-sm text-muted-foreground hover:bg-amber-500/10 hover:text-amber-600 dark:hover:text-amber-400">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="flex gap-2 pt-3">
                @auth
                    <x-ui.button class="flex-1" :href="auth()->user()->isStaff() ? route('admin.dashboard') : route('akun')" icon="user-round">
                        {{ auth()->user()->isStaff() ? 'Dashboard' : 'Akun Saya' }}
                    </x-ui.button>
                @else
                    <x-ui.button class="flex-1" variant="outline" :href="route('login')">Masuk</x-ui.button>
                    <x-ui.button class="flex-1" :href="route('donasi')" icon="hand-heart">Donasi</x-ui.button>
                @endauth
            </div>
        </div>
    </div>
</header>
