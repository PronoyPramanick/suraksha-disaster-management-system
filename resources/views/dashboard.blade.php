<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0">{{ __('Dashboard') }}</h1>
    </x-slot>

    <div class="container-xl py-4">
        <div class="dashboard-welcome-card card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <span class="map-eyebrow"><span class="map-live-dot"></span> COMMUNITY RESPONSE PLATFORM</span>
                <h2 class="h3 mt-3 mb-2">Welcome, {{ Auth::user()->name }}</h2>
                <p class="text-secondary mb-4">{{ __('Coordinate disaster information and community response from one place.') }}</p>

                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-success" href="{{ route('alerts.index') }}">{{ auth()->user()->role === 'admin' ? __('Disaster News & Alerts') : __('View Disaster News & Alerts') }}</a>
                    <a class="btn btn-outline-success" href="{{ route('rescue-requests.index') }}">{{ auth()->user()->role === 'admin' ? __('Manage Rescue Requests') : __('My Rescue Requests') }}</a>
                    <a class="btn btn-outline-success" href="{{ route('missing-persons.index') }}">Missing Person Reports</a>
                    <a class="btn btn-outline-success" href="{{ route(in_array(auth()->user()->role, ['admin', 'volunteer'], true) ? 'volunteers.index' : 'volunteers.create') }}">{{ auth()->user()->role === 'admin' ? 'Review Volunteer Applications' : (auth()->user()->role === 'volunteer' ? 'My Volunteer Application' : 'Apply to Volunteer') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
