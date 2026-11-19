<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="map-eyebrow">COMMUNITY RESPONSE</span>
                <h1 class="h3 mb-0 mt-2">{{ $admin ? 'Volunteer Applications' : 'My Volunteer Application' }}</h1>
            </div>
            @if(!$admin && $applications->isEmpty())
                <a class="btn btn-success" href="{{ route('volunteers.create') }}">Apply to volunteer</a>
            @endif
        </div>
    </x-slot>

    <div class="container-xl py-4">
        @if(session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif

        <div class="d-grid gap-3">
            @forelse($applications as $application)
                @php
                    $statusClass = match ($application->status) {
                        'Approved' => 'text-bg-success',
                        'Rejected' => 'text-bg-danger',
                        default => 'text-bg-warning',
                    };
                @endphp

                <article class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                            <div>
                                <h2 class="h5 mb-1">{{ $admin ? ($application->name ?: $application->user->name) : 'Your application' }}</h2>
                                @if($admin)
                                    <a class="small text-secondary" href="mailto:{{ $application->email ?: $application->user->email }}">{{ $application->email ?: $application->user->email }}</a>
                                @else
                                    <span class="small text-secondary">Submitted {{ $application->created_at->format('M j, Y') }}</span>
                                @endif
                            </div>
                            <span class="badge rounded-pill {{ $statusClass }}">{{ $application->status }}</span>
                        </div>

                        @if($application->status === 'Approved' && $application->user->volunteer_id)
                            <div class="volunteer-id-notice mb-4">
                                <span class="small fw-semibold text-uppercase">Assigned Volunteer ID</span>
                                <strong>{{ $application->user->volunteer_id }}</strong>
                                @unless($admin)<span>Use this ID and your password on the sign-in page.</span>@endunless
                            </div>
                        @endif

                        <dl class="row mb-0 volunteer-application-details">
                            <dt class="col-sm-3">Mobile number</dt><dd class="col-sm-9">{{ $application->phone }}</dd>
                            <dt class="col-sm-3">Address</dt><dd class="col-sm-9">{{ $application->address ?: 'Not provided' }}</dd>
                            <dt class="col-sm-3">Blood group</dt><dd class="col-sm-9">{{ $application->blood_group ?: 'Not provided' }}</dd>
                            <dt class="col-sm-3">Skills</dt><dd class="col-sm-9 text-break">{{ $application->skills }}</dd>
                            <dt class="col-sm-3">Availability</dt><dd class="col-sm-9">{{ $application->availability }}</dd>
                            <dt class="col-sm-3">Relevant experience</dt><dd class="col-sm-9 text-break">{{ $application->experience ?: 'Not provided' }}</dd>
                        </dl>

                        @if($admin && $application->status === 'Pending')
                            <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                                <form method="POST" action="{{ route('volunteers.review', $application) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-success" name="status" value="Approved" type="submit">Approve and grant volunteer access</button>
                                </form>
                                <form method="POST" action="{{ route('volunteers.review', $application) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-outline-danger" name="status" value="Rejected" type="submit">Reject</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-secondary">
                        {{ $admin ? 'There are no volunteer applications yet.' : 'You have not submitted a volunteer application yet.' }}
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">{{ $applications->links() }}</div>
    </div>
</x-app-layout>
