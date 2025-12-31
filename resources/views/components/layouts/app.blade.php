<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $seoTitle = $title ?? 'Evencapt Olympus - Sports Tournament Platform';
        $seoDesc  = 'Platform manajemen turnamen olahraga paling canggih. Live score, statistik real-time, dan manajemen tim.';
        $seoImage = asset('images/dashboard-preview.png');
        
        if (isset($event) && $event) {
            $seoTitle = $event->name . ' - Official Tournament';
            $seoDesc  = Str::limit(strip_tags($event->public_description), 150) ?? 'Saksikan pertandingan dan statistik live di Evencapt.';
            if ($event->banner_image) {
                $seoImage = Storage::url($event->banner_image);
            }
        }
        
        if (isset($game) && $game) {
            $seoTitle = $game->homeCompetitor->name . ' vs ' . $game->awayCompetitor->name . ' - ' . ($game->category->name ?? 'Match');
            $seoDesc  = 'Live Score: ' . $game->home_score . ' - ' . $game->away_score . '. Nonton statistik pertandingan secara real-time.';
        }
    @endphp

    <title>{{ $seoTitle ?? 'EC-Olympus' }}</title>

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDesc }}">
    <meta property="og:image" content="{{ $seoImage }}">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $seoTitle }}">
    <meta property="twitter:description" content="{{ $seoDesc }}">
    <meta property="twitter:image" content="{{ $seoImage }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
 
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $currentEvent = $event ?? null;
        $currentTenant = $tenant ?? null;
        $currentGame = $game ?? null;

        $primaryColor = '#2563eb';
        
        if ($currentEvent) {
             $primaryColor = $currentEvent->primary_color 
                ?? ($currentEvent->tenant->primary_color ?? $primaryColor);
        } 
        elseif ($currentGame) {
             $primaryColor = $currentGame->tenant->primary_color ?? $primaryColor;
        }
        elseif ($currentTenant) {
             $primaryColor = $currentTenant->primary_color ?? $primaryColor;
        }
    @endphp

    <style>
        :root {
            --theme-color: {{ $primaryColor }};
            --theme-color-dark: color-mix(in srgb, var(--theme-color), black 10%);
            --theme-color-light: color-mix(in srgb, var(--theme-color), white 90%);
        }
        
        .bg-theme { background-color: var(--theme-color) !important; }
        .text-theme { color: var(--theme-color) !important; }
        .border-theme { border-color: var(--theme-color) !important; }
        .hover\:bg-theme:hover { background-color: var(--theme-color) !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <div class="fixed top-0 left-0 w-full h-1 z-[100] pointer-events-none" 
         x-data="{ isLoading: false }" 
         x-init="
            document.addEventListener('livewire:navigating', () => { isLoading = true });
            document.addEventListener('livewire:navigated', () => { isLoading = false });
         ">
        <div x-show="isLoading" 
             class="h-full bg-orange-500 animate-progress origin-left w-full"
             style="box-shadow: 0 0 10px #f97316, 0 0 5px #f97316;">
        </div>
    </div>

    <main>
        {{ $slot }}
    </main>
    
    <style>
        @keyframes progress {
            0% { transform: scaleX(0); }
            50% { transform: scaleX(0.5); }
            100% { transform: scaleX(0.9); }
        }
        .animate-progress {
            animation: progress 2s infinite ease-out;
        }
    </style>
</body>
</html>