<x-app-layout>
    <x-slot name="header">
        <div class="alert-detail-heading"><a class="alert-back-link" href="{{ route('alerts.index') }}">← All news &amp; alerts</a>
            @if (auth()->user()->role === 'admin')<a class="alert-primary-link" href="{{ route('alerts.edit', $alert) }}">Edit update</a>@endif
        </div>
    </x-slot>

    <div class="alert-page-wrap alert-detail-wrap">
        @if (session('status'))<div class="alert-flash" role="status">{{ session('status') }}</div>@endif
        <article class="alert-detail-card">
            <div class="alert-badges"><span class="alert-kind kind-{{ $alert->kind }}">{{ strtoupper($alert->kind) }}</span><span class="alert-severity severity-{{ $alert->severity }}">{{ Str::headline($alert->severity) }}</span><span class="alert-category">{{ Str::headline($alert->category) }}</span></div>
            <h1>{{ $alert->title }}</h1>
            <p class="alert-detail-summary">{{ $alert->summary }}</p>
            <div class="alert-detail-meta"><span><strong>District</strong>{{ $alert->location }}</span><span><strong>Affected Area</strong>{{ $alert->affected_area ?: 'Not specified' }}</span><span><strong>Status</strong>{{ Str::headline($alert->status) }}</span><span><strong>Published</strong><time datetime="{{ $alert->published_at->toIso8601String() }}">{{ $alert->published_at->format('F j, Y · g:i A') }}</time></span></div>
            <div class="alert-detail-body">{!! nl2br(e($alert->body)) !!}</div>
            <section class="alert-safety-guidelines" id="safety-guidelines"><div class="safety-guidelines-heading"><span>⬡</span><div><p>STAY SAFE</p><h2>Safety Guidelines</h2></div></div><div class="alert-detail-body">{!! nl2br(e($alert->safety_instructions ?: 'Please follow the latest instructions from local authorities and emergency services.')) !!}</div></section>
            @if (auth()->user()->role === 'admin')
                <form class="alert-delete-form" method="POST" action="{{ route('alerts.destroy', $alert) }}" onsubmit="return confirm('Delete this update?')">@csrf @method('DELETE')<button type="submit">Delete update</button></form>
            @endif
        </article>
    </div>
</x-app-layout>
