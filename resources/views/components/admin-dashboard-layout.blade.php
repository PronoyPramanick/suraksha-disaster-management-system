<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b2038">
    <title>{{ $title ?? 'Disaster News & Alerts' }} · SURAKSHA</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-dashboard-body">
    <div class="admin-shell" x-data="{ sidebarOpen: false }">
        <div class="admin-mobile-scrim" x-show="sidebarOpen" x-cloak x-on:click="sidebarOpen = false"></div>
        <aside class="admin-sidebar" x-bind:class="sidebarOpen ? 'sidebar-open' : ''">
            <a class="admin-brand" href="{{ route('dashboard') }}">
                <span class="admin-brand-mark"><svg viewBox="0 0 40 44" fill="none" aria-hidden="true"><path d="M20 2 36 8v12c0 10-6 17-16 22C10 37 4 30 4 20V8l16-6Z" stroke="currentColor" stroke-width="2"/><path d="M11 23c3-5 6-5 9 0s6 5 9 0M11 28c3-5 6-5 9 0s6 5 9 0M15 15l5-5 5 5v5H15v-5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 33c4-3 8-3 12 0" stroke="#20c895" stroke-width="2.5" stroke-linecap="round"/></svg></span>
                <span><strong>SURAKSHA</strong><small>DISASTER MANAGEMENT<br>IN BANGLADESH</small></span>
            </a>

            <nav class="admin-side-nav" aria-label="Admin navigation">
                <a href="{{ route('dashboard') }}" class="admin-nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}"><span>⌂</span>Dashboard</a>
                <a href="{{ route('alerts.index') }}" class="admin-nav-link {{ request()->routeIs('alerts.*') ? 'is-active' : '' }}"><span>◖</span>Disaster Alerts</a>
                <a href="{{ route('alerts.index') }}#recent-alerts" class="admin-nav-link"><span>⌖</span>Affected Areas</a>
                <a href="{{ route('alerts.index') }}#safety-guidelines" class="admin-nav-link"><span>⬡</span>Safety Guidelines</a>
            </nav>

            <div class="admin-sidebar-bottom">
                <div class="admin-resilience-note"><span>⬡</span><p>Safer communities<br><strong>Stronger Bangladesh</strong></p></div>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="admin-logout" type="submit">↪ &nbsp;Sign out</button></form>
            </div>
        </aside>

        <div class="admin-main-column">
            <header class="admin-topbar">
                <button class="admin-menu-toggle" type="button" aria-label="Open navigation" x-on:click="sidebarOpen = !sidebarOpen">☰</button>
                <div class="admin-topbar-spacer"></div>
                <span class="admin-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div class="admin-user-name"><strong>{{ auth()->user()->name }}</strong><small>Administrator</small></div>
                <a class="admin-profile-link" href="{{ route('profile.edit') }}" aria-label="Edit profile">⌄</a>
            </header>
            <main class="admin-main-content">{{ $slot }}</main>
        </div>
    </div>
</body>
</html>
