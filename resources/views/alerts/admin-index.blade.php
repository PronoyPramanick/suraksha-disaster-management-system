<x-admin-dashboard-layout>
    <div class="alerts-admin-page-head">
        <div class="alerts-admin-title-group"><span class="alerts-admin-megaphone">◖</span><div><h1>Disaster News &amp; Alerts</h1><p>Monitor and manage active disaster warnings across Bangladesh.</p></div></div>
        <a class="alerts-create-button" href="{{ route('alerts.create') }}"><span>＋</span>Create New Alert</a>
    </div>

    @if (session('status'))<div class="alert-flash admin-alert-flash" role="status">{{ session('status') }}</div>@endif

    <section class="alert-stats-grid" aria-label="Alert statistics">
        <article class="alert-stat-card"><span class="stat-icon stat-blue">♧</span><div><small>Active Alerts</small><strong>{{ str_pad((string) $activeAlerts, 2, '0', STR_PAD_LEFT) }}</strong></div><span class="stat-arrow">›</span></article>
        <article class="alert-stat-card"><span class="stat-icon stat-red">!</span><div><small>Critical</small><strong>{{ str_pad((string) $criticalAlerts, 2, '0', STR_PAD_LEFT) }}</strong></div><span class="stat-arrow">›</span></article>
        <article class="alert-stat-card"><span class="stat-icon stat-green">⌖</span><div><small>Affected Districts</small><strong>{{ str_pad((string) $affectedDistricts, 2, '0', STR_PAD_LEFT) }}</strong></div><span class="stat-arrow">›</span></article>
        <article class="alert-stat-card"><span class="stat-icon stat-purple">▦</span><div><small>Updated Today</small><strong>{{ str_pad((string) $updatedToday, 2, '0', STR_PAD_LEFT) }}</strong></div><span class="stat-arrow">›</span></article>
    </section>

    @if ($featuredAlert)
        <section class="featured-alert-panel {{ $featuredAlert->severity === 'critical' ? 'featured-critical' : '' }}" id="safety-guidelines">
            <div class="featured-alert-main">
                <div class="featured-severity severity-{{ $featuredAlert->severity }}"><span>!</span>{{ strtoupper($featuredAlert->severity) }}</div>
                <div class="featured-title-row"><span class="featured-type-icon">≋</span><div><h2>{{ $featuredAlert->title }}</h2><p>{{ $featuredAlert->summary }}</p></div></div>
                <div class="featured-meta-grid">
                    <span><small>Location</small><strong>{{ $featuredAlert->location }}</strong></span>
                    <span><small>Published</small><strong>{{ $featuredAlert->published_at->format('M j, Y · g:i A') }}</strong></span>
                    <span><small>Affected Area</small><strong>{{ $featuredAlert->affected_area ?: 'Not specified' }}</strong></span>
                    <span><small>Status</small><strong><i class="status-pill status-{{ $featuredAlert->status }}">{{ Str::headline($featuredAlert->status) }}</i></strong></span>
                </div>
            </div>
            <aside class="featured-safety-note"><span class="safety-shield">⬡</span><div><strong>Safety Instructions</strong><p>{{ Str::limit($featuredAlert->safety_instructions ?: 'No safety instructions have been added yet.', 160) }}</p></div></aside>
            <div class="featured-alert-actions">
                <a class="alert-action-button action-view" href="{{ route('alerts.show', $featuredAlert) }}">◉ &nbsp;View Details</a>
                <a class="alert-action-button action-edit" href="{{ route('alerts.edit', $featuredAlert) }}">✎ &nbsp;Edit Alert</a>
                <form method="POST" action="{{ route('alerts.destroy', $featuredAlert) }}" onsubmit="return confirm('Delete this update?')">@csrf @method('DELETE')<button class="alert-action-button action-delete" type="submit">♲ &nbsp;Delete</button></form>
            </div>
        </section>
    @else
        <section class="featured-alert-empty"><div class="stat-icon stat-blue">◖</div><div><h2>No active alerts</h2><p>Create a disaster news or alert update to share verified information with the community.</p></div></section>
    @endif

    <section class="recent-alerts-panel" id="recent-alerts">
        <div class="recent-alerts-heading"><h2>Recent Alerts</h2><a href="#recent-alerts">Showing {{ $alerts->count() }} recent</a></div>
        @if ($alerts->isEmpty())
            <div class="recent-alerts-empty">Published alerts will appear in this table.</div>
        @else
            <div class="recent-alerts-table-wrap"><table class="recent-alerts-table">
                <thead><tr><th>Alert</th><th>Type</th><th>Location</th><th>Severity</th><th>Published</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                    @foreach ($alerts as $alert)
                        <tr>
                            <td><a class="table-alert-title" href="{{ route('alerts.show', $alert) }}"><span class="table-alert-icon">≋</span><span><strong>{{ $alert->title }}</strong><small>{{ Str::limit($alert->summary, 58) }}</small></span></a></td>
                            <td><span class="table-type-pill">{{ Str::headline($alert->category) }}</span></td>
                            <td><span class="table-location">⌖ &nbsp;{{ $alert->location }}<small>{{ $alert->affected_area ?: 'District' }}</small></span></td>
                            <td><span class="table-severity severity-{{ $alert->severity }}">{{ Str::headline($alert->severity) }}</span></td>
                            <td><time class="table-time" datetime="{{ $alert->published_at->toIso8601String() }}">{{ $alert->published_at->format('M j, Y') }}<small>{{ $alert->published_at->format('g:i A') }}</small></time></td>
                            <td><span class="status-pill status-{{ $alert->status }}">{{ Str::headline($alert->status) }}</span></td>
                            <td><div class="table-row-actions"><a href="{{ route('alerts.show', $alert) }}" aria-label="View {{ $alert->title }}">◉</a><a href="{{ route('alerts.edit', $alert) }}" aria-label="Edit {{ $alert->title }}">✎</a><form method="POST" action="{{ route('alerts.destroy', $alert) }}" onsubmit="return confirm('Delete this update?')">@csrf @method('DELETE')<button type="submit" aria-label="Delete {{ $alert->title }}">♲</button></form></div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table></div>
            <div class="admin-alert-pagination">{{ $alerts->links() }}</div>
        @endif
    </section>
</x-admin-dashboard-layout>
