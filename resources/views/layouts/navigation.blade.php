<nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm" x-data="{ open: false, accountOpen: false }">
    <div class="container-xl">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <x-application-logo class="brand-logo text-success" />
            <span class="fw-bold text-success">SURAKSHA</span>
        </a>

        <button class="navbar-toggler" type="button" aria-label="Toggle navigation" x-on:click="open = !open" x-bind:aria-expanded="open.toString()">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" x-bind:class="{ 'show': open }">
            <div class="navbar-nav me-auto mb-2 mb-lg-0">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-nav-link>
                <x-nav-link :href="route('alerts.index')" :active="request()->routeIs('alerts.*')">
                    {{ auth()->user()->role === 'admin' ? __('Disaster News & Alerts') : __('View Disaster News & Alerts') }}
                </x-nav-link>
                <x-nav-link :href="route('rescue-requests.index')" :active="request()->routeIs('rescue-requests.*')">
                    {{ auth()->user()->role === 'admin' ? __('Rescue Requests') : __('My Rescue Requests') }}
                </x-nav-link>
            </div>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" x-on:click="accountOpen = !accountOpen" x-bind:aria-expanded="accountOpen.toString()">
                    {{ Auth::user()->name }}
                </button>
                <div class="dropdown-menu dropdown-menu-end" x-bind:class="{ 'show': accountOpen }" x-cloak>
                    <div class="px-3 py-2">
                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item" type="submit">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
