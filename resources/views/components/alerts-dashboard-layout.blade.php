<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0b2038">
    <title>Disaster News &amp; Alerts · SURAKSHA</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="alerts-dashboard-body">
    @php($isAdmin = auth()->user()->role === 'admin')
    <div class="alerts-dashboard-shell" x-data="{ sidebarCollapsed: false, mobileSidebarOpen: false }" x-bind:class="sidebarCollapsed ? 'alerts-sidebar-is-collapsed' : ''">
        <div class="alerts-sidebar-scrim" x-show="mobileSidebarOpen" x-cloak x-on:click="mobileSidebarOpen = false"></div>
        <aside class="alerts-sidebar" x-bind:class="mobileSidebarOpen ? 'alerts-sidebar-open' : ''">
            <a class="alerts-brand" href="{{ route('dashboard') }}"><span class="alerts-brand-mark"><svg viewBox="0 0 44 52" fill="none" aria-hidden="true"><path d="M22 2 41 9v15c0 12-7 20-19 26C10 44 3 36 3 24V9l19-7Z" stroke="currentColor" stroke-width="2.7"/><path d="M12 27c3.3-5.5 6.7-5.5 10 0s6.7 5.5 10 0M12 33c3.3-5.5 6.7-5.5 10 0s6.7 5.5 10 0M17 18l5-5 5 5v6H17v-6Z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 39c4.7-3.4 9.3-3.4 14 0" stroke="#22c997" stroke-width="3" stroke-linecap="round"/></svg></span><span class="alerts-brand-copy"><strong>SURAKSHA</strong><small>Disaster Management System<br>in Bangladesh</small></span></a>

            <nav class="alerts-sidebar-nav" aria-label="Main navigation">
                <a class="alerts-side-link {{ request()->routeIs('dashboard') ? 'side-link-active' : '' }}" href="{{ route('dashboard') }}"><span>⌂</span><b>Dashboard</b></a>
                <a class="alerts-side-link {{ request()->routeIs('alerts.*') ? 'side-link-active' : '' }}" href="{{ route('alerts.index') }}"><span>⚑</span><b>Disaster News &amp; Alerts</b></a>
            </nav>

            <div class="alerts-sidebar-bottom"><span>⬡</span><p>Safer Communities<br><strong>Stronger Bangladesh</strong></p><svg class="bangladesh-mark" viewBox="0 0 64 80" fill="currentColor" aria-hidden="true"><path d="m34 3 7 9-2 10 9 3-4 8 8 7-7 9 5 7-9 5 1 9-11 2-4 7-8-8-8 1 2-10-7-5 6-8-5-8 8-7-1-9 9-4 1-9 8 1Z" opacity=".17"/></svg></div>
        </aside>

        <div class="alerts-main-column">
            <header class="alerts-topbar">
                <button class="alerts-menu-button" type="button" aria-label="Toggle navigation" x-on:click="if (window.matchMedia('(max-width: 760px)').matches) { mobileSidebarOpen = !mobileSidebarOpen } else { sidebarCollapsed = !sidebarCollapsed }">☰</button>
                <form class="alerts-global-search" method="GET" action="{{ route('alerts.index') }}"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="8.7" cy="8.7" r="5.7" stroke="currentColor" stroke-width="1.5"/><path d="m13 13 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg><input type="search" name="q" value="{{ request('q') }}" placeholder="Search alerts, locations..." aria-label="Search alerts and locations"></form>
                <button class="alerts-notification-button" type="button" aria-label="Notifications"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M15.5 7.8a5.5 5.5 0 0 0-11 0c0 6-2 6-2 7.2h15c0-1.2-2-1.2-2-7.2ZM8 17h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg><i></i></button>
                <details class="alerts-user-menu"><summary><span class="alerts-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span class="alerts-user-label"><strong>{{ $isAdmin ? 'Admin' : auth()->user()->name }}</strong></span><span class="alerts-user-chevron">⌄</span></summary><div class="alerts-user-dropdown"><a href="{{ route('dashboard') }}">Dashboard</a><a href="{{ route('profile.edit') }}">Profile</a><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Sign out</button></form></div></details>
            </header>
            <main class="alerts-dashboard-content">{{ $slot }}</main>
        </div>
    </div>
</body>
</html>
