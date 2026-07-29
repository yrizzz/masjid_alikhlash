@props([
    'title'       => null,
    'description' => null,
    'hero'        => false,
])

@php
    $siteName = setting('name', config('masjid.name'));
    $pageTitle = $title ? $title.' — '.$siteName : $siteName.' · '.config('masjid.tagline');
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-hidden max-w-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description ?? setting('site_description', config('masjid.tagline').' — '.config('masjid.address')) }}">
    <meta name="theme-color" content="#0f766e">

    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description ?? config('masjid.tagline') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <link rel="apple-touch-icon" href="/icon-192.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <script>
        // Cegah kedip tema gelap sebelum CSS termuat.
        (function () {
            try {
                var t = localStorage.getItem('ak_theme');
                var dark = t === '"dark"' || (t === null && matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', dark);
            } catch (e) {}
        })();
    </script>

    <script>
        function triggerHeroAnimations() {
            document.querySelectorAll('.hero-animate').forEach(function (el) {
                el.classList.remove('hero-animate');
                void el.offsetWidth; // force reflow
                el.classList.add('hero-animate');
            });
        }

        function initScrollAnimations() {
            var els = document.querySelectorAll('.scroll-animate');
            if (!els.length) return;
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        var el = entry.target;
                        var delay = el.dataset.scrollDelay || 0;
                        setTimeout(function () { el.classList.add('is-visible'); }, parseInt(delay));
                        observer.unobserve(el);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
            els.forEach(function (el) { observer.observe(el); });
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
</head>
<body x-data class="min-h-screen bg-background font-sans text-foreground antialiased overflow-x-hidden max-w-full">
    @include('partials.public.navbar')

    <main class="{{ $hero ? '' : 'pt-16 lg:pt-[4.5rem]' }} pb-6 lg:pb-0">
        {{ $slot }}
    </main>

    @include('partials.public.footer')
    @include('partials.public.bottom-nav')

    <x-ui.toaster />
    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            triggerHeroAnimations();
            initScrollAnimations();
        });
        document.addEventListener('livewire:navigated', function () {
            document.querySelectorAll('.scroll-animate').forEach(function(el) {
                el.classList.remove('is-visible');
            });
            triggerHeroAnimations();
            initScrollAnimations();
        });
    </script>
    @stack('scripts')
</body>
</html>

