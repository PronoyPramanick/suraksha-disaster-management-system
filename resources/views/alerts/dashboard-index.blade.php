<x-alerts-dashboard-layout>
    <div class="alerts-dashboard-heading">
        <div class="alerts-heading-copy"><span class="alerts-heading-icon">◖</span><div><h1>Disaster News &amp; Alerts</h1><p>Stay informed with the latest disaster alerts and important announcements across Bangladesh.</p></div></div>
        @if (auth()->user()->role === 'admin')<a class="alerts-new-button" href="{{ route('alerts.create') }}"><span>＋</span>Create New Alert</a>@endif
    </div>

    <section class="alerts-summary-grid" aria-label="Alert overview">
        <article class="alerts-summary-card"><span class="summary-icon summary-red">!</span><div><small>Total Alerts</small><strong>{{ $totalAlerts }}</strong></div><span class="summary-arrow">›</span></article>
        <article class="alerts-summary-card"><span class="summary-icon summary-red">!</span><div><small>Active Alerts</small><strong>{{ $activeAlerts }}</strong></div><span class="summary-arrow">›</span></article>
        <article class="alerts-summary-card"><span class="summary-icon summary-green">✓</span><div><small>Resolved Alerts</small><strong>{{ $resolvedAlerts }}</strong></div><span class="summary-arrow">›</span></article>
        <article class="alerts-summary-card"><span class="summary-icon summary-blue">▦</span><div><small>Last Updated</small><strong class="summary-date">{{ $lastUpdated ? \Illuminate\Support\Carbon::parse($lastUpdated)->format('M j, Y') : '—' }}</strong></div><span class="summary-arrow">›</span></article>
    </section>

    <form class="alerts-filter-bar" method="GET" action="{{ route('alerts.index') }}">
        <select name="category" aria-label="Filter by disaster type"><option value="">All Disaster Types</option>@foreach (['cyclone', 'flood', 'storm', 'river_level', 'shelter', 'health', 'rescue', 'fire', 'earthquake', 'landslide', 'general'] as $type)<option value="{{ $type }}" @selected($category === $type)>{{ Str::headline($type) }}</option>@endforeach</select>
        <select name="severity" aria-label="Filter by severity"><option value="">All Severities</option>@foreach (['critical', 'high', 'moderate'] as $level)<option value="{{ $level }}" @selected($severity === $level)>{{ Str::headline($level) }}</option>@endforeach</select>
        <select name="status" aria-label="Filter by status"><option value="">All Status</option>@foreach (['active', 'resolved'] as $state)<option value="{{ $state }}" @selected($status === $state)>{{ Str::headline($state) }}</option>@endforeach</select>
        <label class="alerts-filter-search"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="8.7" cy="8.7" r="5.7" stroke="currentColor" stroke-width="1.5"/><path d="m13 13 4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg><input type="search" name="q" value="{{ $search }}" placeholder="Search by title or location..." aria-label="Search by title or location"></label>
        <button class="alerts-filter-submit" type="submit">Filter</button>
        @if ($category || $severity || $status || $search !== '')<a class="alerts-filter-reset" href="{{ route('alerts.index') }}">Reset</a>@endif
    </form>

    <section class="alerts-results-list" id="alerts-list" aria-label="Disaster alerts">
        @forelse ($alerts as $alert)
            @php($categoryIcon = ['flood' => '≋', 'river_level' => '≋', 'cyclone' => '◉', 'storm' => '☁', 'earthquake' => '⌁', 'landslide' => '▲', 'fire' => '♨', 'health' => '✚', 'rescue' => '✚', 'shelter' => '⌂'][$alert->category] ?? '≋')
            <article class="alerts-result-card result-{{ $alert->severity }}">
                <span class="result-disaster-icon icon-{{ $alert->category }}" aria-hidden="true">{{ $categoryIcon }}</span>
                <div class="result-description">
                    <div class="result-badges"><span class="result-severity result-severity-{{ $alert->severity }}">{{ strtoupper($alert->severity) }}</span><span class="result-type">{{ Str::headline($alert->category) }}</span></div>
                    <h2><a href="{{ route('alerts.show', $alert) }}">{{ $alert->title }}</a></h2>
                    <p>{{ $alert->summary }}</p>
                </div>
                <div class="result-location"><strong><span>⌖</span>{{ $alert->location }}</strong><small>{{ $alert->affected_area ?: 'Affected area not specified' }}</small><time datetime="{{ $alert->published_at->toIso8601String() }}">▣ &nbsp;{{ $alert->published_at->format('M j, Y · g:i A') }}</time></div>
                <span class="result-status result-status-{{ $alert->status }}">{{ Str::headline($alert->status) }}</span>
                <div class="result-actions">
                    <a class="result-view-button" href="{{ route('alerts.show', $alert) }}">◉ &nbsp;View Details</a>
                    @if (auth()->user()->role === 'admin')
                        <a class="result-edit-button" href="{{ route('alerts.edit', $alert) }}" aria-label="Edit {{ $alert->title }}">✎ <span>Edit</span></a>
                        <form method="POST" action="{{ route('alerts.destroy', $alert) }}" onsubmit="return confirm('Delete this alert?')">@csrf @method('DELETE')<button class="result-delete-button" type="submit" aria-label="Delete {{ $alert->title }}">♲ <span>Delete</span></button></form>
                    @else
                        <a class="result-safety-button" href="{{ route('alerts.show', $alert) }}#safety-guidelines">Safety Guidelines</a>
                    @endif
                </div>
            </article>
        @empty
            <div class="alerts-no-results"><span>⌕</span><h2>No matching alerts</h2><p>Try changing the filters or search terms.</p></div>
        @endforelse
    </section>

    <section class="rescue-alert-tracker" aria-labelledby="rescue-alert-tracker-title">
        <div class="rescue-alert-tracker-heading">
            <div>
                <span class="alerts-eyebrow">LIVE RESPONSE</span>
                <h2 id="rescue-alert-tracker-title">Rescue Request Tracker</h2>
                <p>New requests and status changes appear here. Requests are visible only to their owner and authorized administrators.</p>
            </div>
            <a href="{{ route('rescue-requests.index') }}">Open full tracker <span aria-hidden="true">→</span></a>
        </div>

        @if (session('status'))<div class="alert-flash" role="status">{{ session('status') }}</div>@endif

        @forelse ($rescueRequests as $rescueRequest)
            <article class="rescue-alert-card">
                <div class="rescue-alert-card-main">
                    <div class="rescue-alert-meta">
                        <strong>Request #{{ $rescueRequest->id }}</strong>
                        <span class="rescue-request-status status-{{ \Illuminate\Support\Str::slug($rescueRequest->status) }}">{{ $rescueRequest->status }}</span>
                        <span class="rescue-request-priority priority-{{ strtolower($rescueRequest->urgency) }}">{{ $rescueRequest->urgency }} urgency</span>
                    </div>
                    <h3>{{ $rescueRequest->location }}</h3>
                    <p>{{ $rescueRequest->description }}</p>
                    <div class="rescue-alert-details">
                        <span>{{ $rescueRequest->people_count === 1 ? '1 person needs assistance' : $rescueRequest->people_count.' people need assistance' }}</span>
                        @if (auth()->user()->role === 'admin')
                            <span>Contact: {{ $rescueRequest->contact_name }} · {{ $rescueRequest->contact_phone }}</span>
                        @endif
                        <time datetime="{{ $rescueRequest->updated_at->toIso8601String() }}">Updated {{ $rescueRequest->updated_at->diffForHumans() }}</time>
                    </div>
                </div>
                @if (auth()->user()->role === 'admin')
                    <form method="POST" action="{{ route('rescue-requests.status', $rescueRequest) }}" class="rescue-alert-update">
                        @csrf @method('PATCH')
                        <label class="visually-hidden" for="rescue-status-{{ $rescueRequest->id }}">Update status for request #{{ $rescueRequest->id }}</label>
                        <select id="rescue-status-{{ $rescueRequest->id }}" name="status" class="form-select form-select-sm">
                            @foreach (['Pending', 'In Progress', 'Rescued'] as $requestStatus)
                                <option value="{{ $requestStatus }}" @selected($rescueRequest->status === $requestStatus)>{{ $requestStatus }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                @endif
            </article>
        @empty
            <div class="rescue-alert-empty">No rescue requests to track yet.</div>
        @endforelse

        @if ($rescueRequests->hasPages())
            <div class="rescue-alert-pagination">{{ $rescueRequests->links('pagination::bootstrap-5') }}</div>
        @endif
    </section>

    <footer class="alerts-list-footer"><span>Showing {{ $alerts->firstItem() ?? 0 }}–{{ $alerts->lastItem() ?? 0 }} of {{ $alerts->total() }} alerts</span><div>{{ $alerts->links() }}</div></footer>
</x-alerts-dashboard-layout>
