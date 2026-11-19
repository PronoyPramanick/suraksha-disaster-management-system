<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="map-eyebrow">EMERGENCY RESPONSE</span>
                <h1 class="h3 mb-0 mt-2">{{ auth()->user()->role === 'admin' ? 'Rescue Requests' : 'My Rescue Requests' }}</h1>
            </div>
            @if (auth()->user()->role !== 'admin')
                <a href="{{ route('rescue-requests.create') }}" class="btn btn-danger">Request assistance</a>
            @endif
        </div>
    </x-slot>

    <div class="container-xl py-4">
        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif
        @if (session('rescue_request_reference'))
            <div class="alert alert-info" role="status">Your new request reference is <strong>#{{ session('rescue_request_reference') }}</strong>. Save this number to identify your request.</div>
        @endif
        @if (auth()->user()->role === 'admin')
            <p class="text-secondary mb-4">Review incoming requests and update their response status. Contact details are visible to authorized administrators.</p>
        @endif

        <div class="card border-0 shadow-sm">
            @if ($requests->isEmpty())
                <div class="card-body p-5 text-center">
                    <h2 class="h5">No rescue requests yet</h2>
                    <p class="text-secondary mb-4">Requests you submit will appear here with their latest status.</p>
                    @if (auth()->user()->role !== 'admin')
                        <a href="{{ route('rescue-requests.create') }}" class="btn btn-danger">Submit a request</a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                @foreach (['Reference', 'Contact', 'Location and emergency', 'People', 'Urgency', 'Status', 'Submitted'] as $heading)
                                    <th scope="col" class="text-nowrap small text-uppercase">{{ $heading }}</th>
                                @endforeach
                                @if (auth()->user()->role === 'admin')<th scope="col" class="text-nowrap small text-uppercase">Update status</th>@endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $rescueRequest)
                                <tr>
                                    <td class="text-nowrap fw-semibold">#{{ $rescueRequest->id }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $rescueRequest->contact_name }}</div>
                                        <a class="small text-secondary" href="tel:{{ $rescueRequest->contact_phone }}">{{ $rescueRequest->contact_phone }}</a>
                                        @if (auth()->user()->role === 'admin')<div class="small text-secondary">{{ $rescueRequest->user->email }}</div>@endif
                                    </td>
                                    <td class="rescue-request-description">
                                        <div class="fw-semibold">{{ $rescueRequest->location }}</div>
                                        <p class="small text-secondary mb-0 mt-1">{{ $rescueRequest->description }}</p>
                                    </td>
                                    <td>{{ $rescueRequest->people_count }}</td>
                                    <td><span class="badge {{ $rescueRequest->urgency === 'Critical' ? 'text-bg-danger' : ($rescueRequest->urgency === 'High' ? 'text-bg-warning' : 'text-bg-info') }}">{{ $rescueRequest->urgency }}</span></td>
                                    <td><span class="badge {{ ['Pending' => 'text-bg-secondary', 'In Progress' => 'text-bg-primary', 'Rescued' => 'text-bg-success'][$rescueRequest->status] }}">{{ $rescueRequest->status }}</span></td>
                                    <td class="text-nowrap small">{{ $rescueRequest->created_at->format('M j, Y g:i A') }}</td>
                                    @if (auth()->user()->role === 'admin')
                                        <td>
                                            <form method="POST" action="{{ route('rescue-requests.status', $rescueRequest) }}" class="d-flex gap-2">
                                                @csrf @method('PATCH')
                                                <select name="status" aria-label="Status for request #{{ $rescueRequest->id }}" class="form-select form-select-sm">
                                                    <option value="Pending" @selected($rescueRequest->status === 'Pending')>Pending</option>
                                                    <option value="In Progress" @selected($rescueRequest->status === 'In Progress')>In Progress</option>
                                                    <option value="Rescued" @selected($rescueRequest->status === 'Rescued')>Rescued</option>
                                                </select>
                                                <button class="btn btn-sm btn-dark" type="submit">Save</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">{{ $requests->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
