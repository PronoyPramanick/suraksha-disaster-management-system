<x-app-layout>
    <x-slot name="header"><h2 class="h4 mb-0">{{ __('Dashboard') }}</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __('Welcome to the Suraksha community response platform.') }}</p>
                    <a class="alert-primary-link mt-5" href="{{ route('alerts.index') }}">{{ auth()->user()->role === 'admin' ? __('Disaster News & Alerts') : __('View Disaster News & Alerts') }}</a>
                    <a class="alert-primary-link mt-5" href="{{ route('rescue-requests.index') }}">{{ auth()->user()->role === 'admin' ? __('Manage Rescue Requests') : __('My Rescue Requests') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
