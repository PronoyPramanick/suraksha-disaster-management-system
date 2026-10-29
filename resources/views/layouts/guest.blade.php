<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="theme-color" content="#0b1d24">
        <meta name="description" content="Sign in to Suraksha, the community disaster intelligence and response platform.">
        <title>@if (request()->routeIs('login')) Sign in @elseif (request()->routeIs('register')) Create account @else Account access @endif · Suraksha</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        @if (request()->routeIs('login', 'register'))
            {{ $slot }}
        @else
            <div class="guest-fallback">
                <a href="{{ url('/') }}" aria-label="Suraksha home"><x-application-logo class="guest-fallback-logo" /></a>
                <div class="guest-fallback-card">{{ $slot }}</div>
            </div>
        @endif
    </body>
</html>
