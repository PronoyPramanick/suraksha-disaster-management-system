<x-app-layout>
    <x-slot name="header"><h2 class="h4 mb-0">{{ __('Dashboard') }}</h2></x-slot>

    <div class="container-xl py-5 px-3 px-sm-4 px-lg-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <p>{{ __('Welcome to the Suraksha community response platform.') }}</p>
                <div class="d-flex flex-wrap gap-2 mt-5">
                    <a class="alert-primary-link" href="{{ route('alerts.index') }}">{{ auth()->user()->role === 'admin' ? __('Disaster News & Alerts') : __('View Disaster News & Alerts') }}</a>
                    <a class="alert-primary-link" href="{{ route('affected-areas.index') }}">{{ __('Affected Area') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
