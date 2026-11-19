<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0">{{ __('Dashboard') }}</h1>
    </x-slot>

    <div class="container-xl py-4 py-lg-5">
        <section class="dashboard-hero mb-4">
            <div class="dashboard-hero-copy">
                <span class="dashboard-live-label"><i></i> COMMUNITY RESPONSE</span>
                <h2>Prepared, informed, and ready to help.</h2>
                <p>Find verified disaster updates, request urgent assistance, and follow response progress from one place.</p>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a class="btn btn-light fw-semibold" href="{{ route('alerts.index') }}">View disaster alerts <span aria-hidden="true">→</span></a>
                    @if ($user->role === 'admin')
                        <a class="btn btn-outline-light" href="{{ route('admin.panel') }}">Open admin panel</a>
                        <a class="btn btn-outline-light" href="{{ route('rescue-requests.index') }}">Manage rescue requests</a>
                    @elseif ($user->role === 'volunteer')
                        <a class="btn btn-outline-light" href="{{ route('rescue-requests.index') }}">View citizen requests</a>
                    @else
                        <a class="btn btn-outline-light" href="{{ route('rescue-requests.create') }}">Request assistance</a>
                    @endif
                    <a class="btn btn-outline-light" href="{{ route('missing-persons.index') }}">Missing person reports</a>
                    <a class="btn btn-outline-light" href="{{ route(in_array($user->role, ['admin', 'volunteer'], true) ? 'volunteers.index' : 'volunteers.create') }}">{{ $user->role === 'admin' ? 'Volunteer applications' : ($user->role === 'volunteer' ? 'My volunteer application' : 'Apply to volunteer') }}</a>
                    <a class="btn btn-outline-light" href="{{ route('affected-areas.index') }}">Affected areas</a>
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
