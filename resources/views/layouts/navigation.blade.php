<nav class="navbar navbar-expand-sm bg-white border-bottom shadow-sm">
    <div class="container-xl">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}" aria-label="Suraksha dashboard">
            <x-application-logo class="brand-logo text-success" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="main-navigation">
            <ul class="navbar-nav me-auto mb-2 mb-sm-0">
                <li class="nav-item"><x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-nav-link></li>
                <li class="nav-item"><x-nav-link :href="route('alerts.index')" :active="request()->routeIs('alerts.*')">{{ auth()->user()->role === 'admin' ? __('Disaster News & Alerts') : __('View Disaster News & Alerts') }}</x-nav-link></li>
            </ul>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">{{ Auth::user()->name }}</button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                    <li><form method="POST" action="{{ route('logout') }}">@csrf<button class="dropdown-item" type="submit">{{ __('Log Out') }}</button></form></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
