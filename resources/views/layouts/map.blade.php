<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b1d24">
    <title>{{ $title ?? 'Disaster Map' }} · Suraksha</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
</head>
<body class="map-site-body">
    <nav class="navbar bg-white border-bottom shadow-sm">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <x-application-logo class="brand-logo text-success" />
                <span class="map-brand-name">SURAKSHA <small>Disaster Intelligence</small></span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <a class="btn btn-outline-success btn-sm" href="{{ route('map.index') }}">Incident Map</a>
                <a class="btn btn-outline-success btn-sm" href="{{ route('affected-areas.index') }}">Affected Areas</a>
                @auth
                    <a class="btn btn-success btn-sm" href="{{ route('alerts.index') }}">Alerts dashboard</a>
                @else
                    <a class="btn btn-success btn-sm" href="{{ route('login') }}">Sign in</a>
                @endauth
            </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    @stack('scripts')
</body>
</html>
