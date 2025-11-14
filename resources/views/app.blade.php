<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  @class(['dark' => ($appearance ?? 'system') == 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Inline script to detect system dark mode preference and apply it immediately --}}
        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                if (appearance === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    if (prefersDark) {
                        document.documentElement.classList.add('dark');
                    }
                }
            })();
        </script>

        {{-- Inline style to set the HTML background color based on our theme in app.css --}}
        <style>
            html {
                /* RuneScape Light Theme - Parchment */
                background-color: hsl(43, 35%, 90%);
            }

            html.dark {
                /* RuneScape Dark Theme - Dungeon */
                background-color: hsl(220, 25%, 8%);
            }
        </style>

        <title inertia>{{ $page['props']['meta']['title'] ?? 'Skillscape - Master Your Journey' }}</title>

        <!-- Open Graph / Facebook / LinkedIn -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $page['props']['meta']['url'] ?? url()->current() }}">
        <meta property="og:title" content="{{ $page['props']['meta']['title'] ?? 'Skillscape - Master Your Journey' }}">
        <meta property="og:description" content="{{ $page['props']['meta']['description'] ?? 'Level up your life like an RPG. Track skills, gain XP, and master your journey with Skillscape.' }}">
        <meta property="og:image" content="{{ $page['props']['meta']['image'] ?? url('/og-image.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:type" content="image/png">
        <meta property="og:image:alt" content="Skillscape - Master Your Journey">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ $page['props']['meta']['url'] ?? url()->current() }}">
        <meta property="twitter:title" content="{{ $page['props']['meta']['title'] ?? 'Skillscape - Master Your Journey' }}">
        <meta property="twitter:description" content="{{ $page['props']['meta']['description'] ?? 'Level up your life like an RPG. Track skills, gain XP, and master your journey with Skillscape.' }}">
        <meta property="twitter:image" content="{{ $page['props']['meta']['image'] ?? url('/og-image.png') }}">

        <link rel="icon" href="/favicon.ico" sizes="32x32">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="manifest" href="/site.webmanifest">
        <meta name="theme-color" content="#D4AF37">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
