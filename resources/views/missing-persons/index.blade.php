<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <div>
                <span class="map-eyebrow">COMMUNITY CARE</span>
                <h1 class="h3 mb-1 mt-2">Missing Persons &amp; Found People</h1>
                <p class="text-secondary mb-0">Share reports and help families reconnect safely.</p>
            </div>
            <span class="missing-report-count">{{ $reports->total() }} report{{ $reports->total() === 1 ? '' : 's' }}</span>
        </div>
    </x-slot>

    <div class="container-xl py-4">
        @if(session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        <section class="missing-action-grid mb-4" aria-label="Submit a person report">
            <a class="missing-action-card missing-action-card-lost" href="{{ route('missing-persons.create', ['type' => 'missing']) }}">
                <span class="missing-action-icon" aria-hidden="true">?</span>
                <span class="missing-action-copy"><strong>Report a Missing Person</strong><small>Share the last known location and details to help locate someone.</small></span>
                <span class="missing-action-arrow" aria-hidden="true">&rarr;</span>
            </a>
            <a class="missing-action-card missing-action-card-found" href="{{ route('missing-persons.create', ['type' => 'found']) }}">
                <span class="missing-action-icon" aria-hidden="true">&#10003;</span>
                <span class="missing-action-copy"><strong>I Found a Person</strong><small>Tell responders where and when you found someone who may need help.</small></span>
                <span class="missing-action-arrow" aria-hidden="true">&rarr;</span>
            </a>
        </section>

        <form method="GET" action="{{ route('missing-persons.index') }}" class="card border-0 shadow-sm missing-search-card mb-4" aria-label="Search reports">
            <div class="card-body row g-3 align-items-end">
                <div class="col-lg-7">
                    <label class="form-label" for="report-search">Search reports</label>
                    <input class="form-control" id="report-search" type="search" name="q" value="{{ $search }}" placeholder="Search a name or location">
                </div>
                <div class="col-lg-3">
                    <label class="form-label" for="report-type">Report type</label>
                    <select class="form-select" id="report-type" name="type">
                        <option value="">All reports</option>
                        <option value="missing" @selected($reportType === 'missing')>Missing person</option>
                        <option value="found" @selected($reportType === 'found')>Found person</option>
                    </select>
                </div>
                <div class="col-lg-2 d-flex gap-2">
                    <button class="btn btn-success flex-grow-1" type="submit">Search</button>
                    <a class="btn btn-outline-secondary" href="{{ route('missing-persons.index') }}">Clear</a>
                </div>
            </div>
        </form>

        <div class="row g-3">
            @forelse($reports as $report)
                @php
                    $isFoundReport = $report->report_type === 'found';
                    $statusClass = match ($report->status) {
                        'Located' => 'text-bg-success',
                        'Closed' => 'text-bg-secondary',
                        default => 'text-bg-warning',
                    };
                @endphp
                <div class="col-lg-6">
                    <article class="card h-100 border-0 shadow-sm missing-person-card {{ $isFoundReport ? 'found-person-card' : '' }}">
                        @if($report->photo_path)
                            <img class="missing-person-photo" src="{{ asset('storage/'.$report->photo_path) }}" alt="Photograph for {{ $isFoundReport ? 'found person' : 'missing person' }} report">
                        @endif
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                <span class="badge {{ $isFoundReport ? 'text-bg-success' : 'text-bg-primary' }}">{{ $isFoundReport ? 'Found person' : 'Missing person' }}</span>
                                <span class="badge rounded-pill {{ $statusClass }}">{{ $report->status }}</span>
                            </div>
                            <h2 class="h5 mb-1">{{ $report->name }}</h2>
                            <p class="small text-secondary mb-3">{{ $report->age !== null ? 'Approx. age '.$report->age.' · ' : '' }}{{ $isFoundReport ? 'Found' : 'Last seen' }} {{ $report->last_known_location }} on {{ $report->last_seen_date }}</p>
                            <p class="mb-2"><strong>Safe contact:</strong> {{ $report->contact_method }}</p>
                            @if($report->details)<p class="missing-person-details mb-3">{{ $report->details }}</p>@endif

                            @if(auth()->user()->role === 'admin' || $report->user_id === auth()->id())
                                <form method="POST" action="{{ route('missing-persons.update', $report) }}" class="d-flex flex-wrap align-items-center gap-2 mt-auto pt-3 border-top">
                                    @csrf
                                    @method('PATCH')
                                    <label class="visually-hidden" for="status-{{ $report->id }}">Update case status</label>
                                    <select class="form-select form-select-sm missing-status-select" id="status-{{ $report->id }}" name="status">
                                        @if(!$isFoundReport)<option value="Missing" @selected($report->status === 'Missing')>Missing</option>@endif
                                        <option value="Located" @selected($report->status === 'Located')>Located</option>
                                        <option value="Closed" @selected($report->status === 'Closed')>Closed</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-dark" type="submit">Update status</button>
                                </form>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="missing-empty-state">
                        <span class="missing-empty-icon" aria-hidden="true">&#9823;</span>
                        <div><h2 class="h5 mb-1">No reports found</h2><p class="text-secondary mb-0">Try another search or submit a new report using one of the buttons above.</p></div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $reports->links() }}</div>
    </div>
</x-app-layout>
