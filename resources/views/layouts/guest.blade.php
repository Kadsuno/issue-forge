<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IssueForge') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Theme Color -->
    <meta name="theme-color" content="#6366f1">
    <meta name="msapplication-TileColor" content="#6366f1">

    <!-- Fonts are loaded via app.css for better caching -->

    <!-- Scripts (Alpine is bundled via Vite in resources/js/app.js) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-dark-900 via-dark-800 to-dark-900 min-h-screen">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-32 w-80 h-80 bg-primary-500/20 rounded-full blur-3xl animate-pulse-slow">
        </div>
        <div class="absolute -bottom-40 -left-32 w-80 h-80 bg-accent-500/20 rounded-full blur-3xl animate-pulse-slow"
            style="animation-delay: 1s;"></div>
    </div>

    <div class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <!-- Logo Section -->
        <div class="mb-8 animate-fade-in">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                <div
                    class="w-12 h-12 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                        </path>
                    </svg>
                </div>
                <div class="text-left">
                    <h1 class="text-xl font-bold text-white">IssueForge</h1>
                    <p class="text-sm text-slate-400">Forge Solutions From Issues</p>
                </div>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md">
            <div class="card p-8 animate-fade-in-up backdrop-blur-sm">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center animate-fade-in" style="animation-delay: 0.3s;">
            <p class="text-xs text-slate-500">
                © {{ date('Y') }} IssueForge. Forge solutions from issues.
            </p>
        </div>
    </div>
</body>

</html>
