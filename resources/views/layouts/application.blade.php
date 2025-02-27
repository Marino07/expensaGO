<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ env('APP_NAME') }}</title>
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>

        <style>
            [x-cloak] { display: none !important; }

            .slider-container {
                position: relative;
                width: 100%;
                height: 100%;
                will-change: transform;  /* Optimizacija za GPU */
            }

            .image-slide {
                opacity: 0;
                transition: opacity 300ms ease-in-out;
                will-change: opacity;
            }

            .image-slide.active {
                opacity: 1;
            }

            /* Optimizirane animacije */
            @keyframes slide-left {
                0% { transform: translate3d(100%, -50%, 0); }
                100% { transform: translate3d(-100%, -50%, 0); }
            }

            .animate-slide-left {
                animation: slide-left 25s linear infinite;
                will-change: transform;
            }

            .animate-slide-left:hover {
                animation-play-state: paused;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>

        {{$slot}}
        @stack('scripts')
        <!-- Scripts -->
        @livewireScripts


    </body>
</html>
