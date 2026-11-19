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
            <div class="dashboard-hero-aside" aria-label="Response snapshot">
                <span>Active disaster alerts</span>
                <strong>{{ $activeAlerts }}</strong>
                <small>Check verified updates for your area.</small>
                <a href="{{ route('alerts.index') }}">Open alerts <span aria-hidden="true">↗</span></a>
            </div>
            <span class="dashboard-hero-orbit dashboard-hero-orbit-one" aria-hidden="true"></span>
            <span class="dashboard-hero-orbit dashboard-hero-orbit-two" aria-hidden="true"></span>
        </section>

        <section class="dashboard-stat-grid mb-4" aria-label="Response overview">
            <a class="dashboard-stat-card" href="{{ route('alerts.index') }}">
                <span class="dashboard-stat-icon stat-icon-alert" aria-hidden="true">!</span>
                <span><small>Active disaster alerts</small><strong>{{ $activeAlerts }}</strong></span>
                <span class="dashboard-stat-arrow" aria-hidden="true">↗</span>
            </a>
            <a class="dashboard-stat-card" href="{{ route('rescue-requests.index') }}">
                <span class="dashboard-stat-icon stat-icon-requests" aria-hidden="true">⌖</span>
                <span><small>{{ $user->role === 'admin' ? 'All rescue requests' : 'Your rescue requests' }}</small><strong>{{ $rescueRequestCount }}</strong></span>
                <span class="dashboard-stat-arrow" aria-hidden="true">↗</span>
            </a>
            <a class="dashboard-stat-card" href="{{ route('rescue-requests.index') }}">
                <span class="dashboard-stat-icon stat-icon-pending" aria-hidden="true">◷</span>
                <span><small>Awaiting response</small><strong>{{ $pendingRequestCount }}</strong></span>
                <span class="dashboard-stat-arrow" aria-hidden="true">↗</span>
            </a>
        </section>

        <div class="row g-4">
            <div class="col-lg-7">
                <section class="dashboard-panel h-100" aria-labelledby="response-activity-title">
                    <div class="dashboard-panel-heading">
                        <div><span class="dashboard-section-kicker">LIVE TRACKING</span><h2 id="response-activity-title">Recent rescue activity</h2></div>
                        <a href="{{ route('rescue-requests.index') }}">Full tracker <span aria-hidden="true">→</span></a>
                    </div>

                    @forelse ($recentRequests as $rescueRequest)
                        <article class="dashboard-request-row">
                            <span class="dashboard-request-mark {{ $rescueRequest->status === 'Rescued' ? 'request-mark-done' : ($rescueRequest->status === 'In Progress' ? 'request-mark-progress' : 'request-mark-pending') }}" aria-hidden="true">{{ $rescueRequest->status === 'Rescued' ? '✓' : '⌖' }}</span>
                            <div class="dashboard-request-copy">
                                <strong>Request #{{ $rescueRequest->id }} · {{ $rescueRequest->location }}</strong>
                                <span>{{ $rescueRequest->people_count }} {{ $rescueRequest->people_count === 1 ? 'person' : 'people' }} · {{ $rescueRequest->urgency }} urgency</span>
                            </div>
                            <div class="dashboard-request-state">
                                <span class="dashboard-status-pill status-{{ \Illuminate\Support\Str::slug($rescueRequest->status) }}">{{ $rescueRequest->status }}</span>
                                <time datetime="{{ $rescueRequest->updated_at->toIso8601String() }}">{{ $rescueRequest->updated_at->diffForHumans() }}</time>
                            </div>
                        </article>
                    @empty
                        <div class="dashboard-empty-state">
                            <span aria-hidden="true">⌖</span>
                            <strong>No rescue activity yet</strong>
                            <p>Submitted requests and their response status will appear here.</p>
                            @if ($user->role !== 'admin')<a href="{{ route('rescue-requests.create') }}">Submit a rescue request</a>@endif
                        </div>
                    @endforelse
                </section>
            </div>

            <div class="col-lg-5">
                <section class="dashboard-panel h-100" aria-labelledby="latest-alerts-title">
                    <div class="dashboard-panel-heading">
                        <div><span class="dashboard-section-kicker">STAY INFORMED</span><h2 id="latest-alerts-title">Latest alerts</h2></div>
                        <a href="{{ route('alerts.index') }}">All alerts <span aria-hidden="true">→</span></a>
                    </div>
                    @forelse ($recentAlerts as $alert)
                        <a class="dashboard-alert-row" href="{{ route('alerts.show', $alert) }}">
                            <span class="dashboard-alert-dot severity-dot-{{ $alert->severity }}" aria-hidden="true"></span>
                            <span class="dashboard-alert-copy"><strong>{{ $alert->title }}</strong><small>{{ $alert->location }} · {{ $alert->published_at->diffForHumans() }}</small></span>
                            <span class="dashboard-alert-chevron" aria-hidden="true">→</span>
                        </a>
                    @empty
                        <div class="dashboard-empty-state dashboard-alert-empty">
                            <span aria-hidden="true">!</span>
                            <strong>No active updates</strong>
                            <p>Verified disaster alerts will appear here when published.</p>
                        </div>
                    @endforelse
                    <a class="dashboard-map-link" href="{{ route('map.index') }}"><span aria-hidden="true">⌖</span><span><strong>Explore the incident map</strong><small>View reported areas and nearby alerts</small></span><span class="dashboard-alert-chevron" aria-hidden="true">→</span></a>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
