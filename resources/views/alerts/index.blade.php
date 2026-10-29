<x-app-layout>
    <x-slot name="header">
        <div class="alert-page-heading">
            <div><p class="alert-eyebrow">COMMUNITY RESPONSE</p><h1>Disaster News &amp; Alerts</h1><p>Verified updates to help communities stay informed and prepared.</p></div>
        </div>
    </x-slot>

    <div class="alert-page-wrap">
        @if (session('status'))<div class="alert-flash" role="status">{{ session('status') }}</div>@endif

        @if ($alerts->isEmpty())
            <section class="alert-empty"><span class="alert-empty-icon">!</span><h2>No updates yet</h2><p>New disaster news and community alerts will appear here.</p></section>
        @else
            <div class="alert-list">
                @foreach ($alerts as $alert)
                    <article class="alert-card">
                        <div class="alert-card-main">
                            <div class="alert-badges"><span class="alert-kind kind-{{ $alert->kind }}">{{ strtoupper($alert->kind) }}</span><span class="alert-severity severity-{{ $alert->severity }}">{{ Str::headline($alert->severity) }}</span><span class="alert-category">{{ Str::headline($alert->category) }}</span></div>
                            <h2><a href="{{ route('alerts.show', $alert) }}">{{ $alert->title }}</a></h2>
                            <p class="alert-card-summary">{{ $alert->summary }}</p>
                            <div class="alert-card-body">{!! nl2br(e($alert->body)) !!}</div>
                            <section class="alert-card-safety" id="safety-guidelines-{{ $alert->id }}"><strong>Safety Instructions</strong><p>{!! nl2br(e($alert->safety_instructions ?: 'Please follow local authority guidance.')) !!}</p></section>
                            <div class="alert-card-meta"><span><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M16 8.3c0 4.8-6 9.2-6 9.2S4 13.1 4 8.3a6 6 0 1 1 12 0Z" stroke="currentColor" stroke-width="1.5"/><circle cx="10" cy="8" r="2" stroke="currentColor" stroke-width="1.5"/></svg>{{ $alert->location }} · {{ $alert->affected_area ?: 'Area not specified' }}</span><time datetime="{{ $alert->published_at->toIso8601String() }}">{{ $alert->published_at->format('M j, Y · g:i A') }}</time></div>
                            <div class="alert-user-actions"><a href="{{ route('alerts.show', $alert) }}">View Details</a><a href="{{ route('alerts.show', $alert) }}#safety-guidelines">Safety Guidelines</a></div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="alert-pagination">{{ $alerts->links() }}</div>
        @endif
    </div>
</x-app-layout>
