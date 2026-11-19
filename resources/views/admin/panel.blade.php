<x-app-layout>
    <x-slot name="header">
        <div class="admin-panel-heading">
            <div>
                <span class="map-eyebrow">SYSTEM ADMINISTRATION</span>
                <h1 class="h3 mb-1 mt-2">Admin Panel</h1>
                <p class="text-secondary mb-0">Review citizen and volunteer accounts, and monitor every rescue request.</p>
            </div>
            <span class="dashboard-role-pill">Administrator</span>
        </div>
    </x-slot>

    <div class="container-xl py-4 py-lg-5">
        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        <section class="admin-panel-stats mb-4" aria-label="System record totals">
            <article class="admin-panel-stat"><span class="admin-panel-stat-icon stat-icon-requests">♙</span><span><small>Citizens</small><strong>{{ number_format($citizenCount) }}</strong></span></article>
            <article class="admin-panel-stat"><span class="admin-panel-stat-icon stat-icon-alert">♟</span><span><small>Volunteers</small><strong>{{ number_format($volunteerCount) }}</strong></span></article>
            <article class="admin-panel-stat"><span class="admin-panel-stat-icon stat-icon-pending">⌖</span><span><small>All rescue requests</small><strong>{{ number_format($requestCount) }}</strong></span></article>
            <article class="admin-panel-stat"><span class="admin-panel-stat-icon stat-icon-pending">◷</span><span><small>Awaiting response</small><strong>{{ number_format($pendingCount) }}</strong></span></article>
        </section>

        <section class="admin-panel-section mb-4" aria-labelledby="community-records-title">
            <div class="admin-panel-section-heading">
                <div>
                    <span class="dashboard-section-kicker">COMMUNITY DIRECTORY</span>
                    <h2 id="community-records-title">Citizen &amp; volunteer records</h2>
                    <p>Account details, volunteer IDs, requests submitted, and response actions recorded for each person.</p>
                </div>
                <span class="admin-panel-count">{{ number_format($members->total()) }} records</span>
            </div>

            @if ($members->isEmpty())
                <div class="admin-panel-empty">No citizen or volunteer accounts are registered yet.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 admin-panel-table">
                        <thead class="table-light"><tr><th scope="col">Person</th><th scope="col">Role</th><th scope="col">Phone</th><th scope="col">Volunteer ID</th><th scope="col">Requests</th><th scope="col">Activity entries</th><th scope="col">Joined</th></tr></thead>
                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td><strong>{{ $member->name }}</strong><small>{{ $member->email }}</small></td>
                                    <td><span class="admin-role-badge {{ $member->role === 'volunteer' ? 'admin-role-volunteer' : 'admin-role-citizen' }}">{{ \Illuminate\Support\Str::headline($member->role) }}</span></td>
                                    <td>{{ $member->phone ?: '—' }}</td>
                                    <td>{{ $member->volunteer_id ?: '—' }}</td>
                                    <td><a class="admin-record-link" href="{{ route('rescue-requests.index', ['member' => $member->id]) }}">{{ $member->rescue_requests_count }} requests</a></td>
                                    <td>{{ $member->rescue_request_activities_count }}</td>
                                    <td class="text-nowrap">{{ $member->created_at->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($members->hasPages())<div class="admin-panel-pagination">{{ $members->links('pagination::bootstrap-5') }}</div>@endif
            @endif
        </section>

        <section class="admin-panel-section" aria-labelledby="all-rescue-requests-title">
            <div class="admin-panel-section-heading">
                <div>
                    <span class="dashboard-section-kicker">RESPONSE ACTIVITY</span>
                    <h2 id="all-rescue-requests-title">All rescue requests</h2>
                    <p>Review request details, response status, and who last updated each request.</p>
                </div>
                <a class="btn btn-outline-success btn-sm" href="{{ route('rescue-requests.index') }}">Open full tracker</a>
            </div>

            @if ($rescueRequests->isEmpty())
                <div class="admin-panel-empty">There are no rescue requests to review.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 admin-panel-table admin-request-table">
                        <thead class="table-light"><tr><th scope="col">Request</th><th scope="col">Citizen / contact</th><th scope="col">Location &amp; emergency</th><th scope="col">People</th><th scope="col">Urgency</th><th scope="col">Status / last activity</th><th scope="col">Update</th></tr></thead>
                        <tbody>
                            @foreach ($rescueRequests as $rescueRequest)
                                <tr>
                                    <td class="text-nowrap"><strong>#{{ $rescueRequest->id }}</strong><small>Updated {{ $rescueRequest->updated_at->diffForHumans() }}</small></td>
                                    <td><strong>{{ $rescueRequest->user->name }}</strong><small>{{ $rescueRequest->user->email }}</small><small>{{ $rescueRequest->contact_name }} · {{ $rescueRequest->contact_phone }}</small></td>
                                    <td class="admin-request-description"><strong>{{ $rescueRequest->location }}</strong><small>{{ $rescueRequest->description }}</small></td>
                                    <td>{{ $rescueRequest->people_count }}</td>
                                    <td><span class="badge {{ $rescueRequest->urgency === 'Critical' ? 'text-bg-danger' : ($rescueRequest->urgency === 'High' ? 'text-bg-warning' : 'text-bg-info') }}">{{ $rescueRequest->urgency }}</span></td>
                                    <td><span class="badge {{ ['Pending' => 'text-bg-secondary', 'In Progress' => 'text-bg-primary', 'Rescued' => 'text-bg-success'][$rescueRequest->status] }}">{{ $rescueRequest->status }}</span>@if ($lastActivity = $rescueRequest->activities->sortByDesc('created_at')->first())<small>{{ $lastActivity->user?->name ?? 'System' }} · {{ $lastActivity->created_at->diffForHumans() }}</small>@endif</td>
                                    <td>
                                        <form method="POST" action="{{ route('rescue-requests.status', $rescueRequest) }}" class="admin-request-status-form">
                                            @csrf @method('PATCH')
                                            <label class="visually-hidden" for="admin-request-status-{{ $rescueRequest->id }}">Update request #{{ $rescueRequest->id }}</label>
                                            <select id="admin-request-status-{{ $rescueRequest->id }}" name="status" class="form-select form-select-sm">
                                                @foreach (['Pending', 'In Progress', 'Rescued'] as $requestStatus)
                                                    <option value="{{ $requestStatus }}" @selected($rescueRequest->status === $requestStatus)>{{ $requestStatus }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($rescueRequests->hasPages())<div class="admin-panel-pagination">{{ $rescueRequests->links('pagination::bootstrap-5') }}</div>@endif
            @endif
        </section>

        <section class="admin-panel-section mt-4" aria-labelledby="activity-log-title">
            <div class="admin-panel-section-heading"><div><span class="dashboard-section-kicker">AUDIT TRAIL</span><h2 id="activity-log-title">Citizen &amp; volunteer activity</h2><p>{{ number_format($activityCount) }} recorded request events, with the person and role responsible for each update.</p></div></div>
            @forelse ($activities as $activity)
                <div class="border-top py-2 small"><strong>Request #{{ $activity->rescue_request_id }}</strong> · {{ $activity->rescueRequest?->user?->name ?? 'Deleted account' }} (citizen request) — {{ $activity->user?->name ?? 'System' }}@if ($activity->user) ({{ ucfirst($activity->user->role) }})@endif {{ $activity->note }} <span class="text-secondary">{{ $activity->created_at->format('M j, Y g:i A') }}</span></div>
            @empty
                <div class="admin-panel-empty">No activity has been recorded yet.</div>
            @endforelse
            @if ($activities->hasPages())<div class="admin-panel-pagination">{{ $activities->links('pagination::bootstrap-5') }}</div>@endif
        </section>
    </div>
</x-app-layout>
