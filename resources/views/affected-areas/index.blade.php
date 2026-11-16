@extends('layouts.map', ['title' => 'Affected Areas'])

@section('content')
<main class="container-xl py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div><span class="text-success fw-bold small">COMMUNITY SITUATION</span><h1 class="mt-2">Affected Areas</h1><p class="text-secondary mb-0">Browse active disaster situations by district, disaster type, and severity.</p></div>
        @auth @if(auth()->user()->role === 'admin')<a class="btn btn-success" href="{{ route('alerts.create') }}">+ Add affected area</a>@endif @endauth
    </div>
    <form class="row g-2 mb-4" method="GET" action="{{ route('affected-areas.index') }}">
        <div class="col-md-5"><label class="visually-hidden" for="area-search">Search area</label><input id="area-search" class="form-control" name="q" value="{{ $search }}" placeholder="Search district or area"></div>
        <div class="col-md-3"><label class="visually-hidden" for="area-category">Disaster type</label><select id="area-category" class="form-select" name="category"><option value="">All disaster types</option>@foreach(['cyclone','flood','storm','river_level','fire','earthquake','landslide','general'] as $item)<option value="{{ $item }}" @selected($category === $item)>{{ Str::headline($item) }}</option>@endforeach</select></div>
        <div class="col-md-2"><label class="visually-hidden" for="area-severity">Severity</label><select id="area-severity" class="form-select" name="severity"><option value="">All severity</option>@foreach(['critical','high','moderate'] as $item)<option value="{{ $item }}" @selected($severity === $item)>{{ Str::headline($item) }}</option>@endforeach</select></div>
        <div class="col-md-2 d-flex gap-2"><button class="btn btn-success flex-grow-1">Filter</button><a class="btn btn-outline-secondary" href="{{ route('affected-areas.index') }}">Clear</a></div>
    </form>
    <div class="row g-3">
    @forelse($areas as $area)
        <div class="col-lg-6"><article class="card h-100 border-0 shadow-sm"><div class="card-body p-4">
            <div class="d-flex justify-content-between gap-2"><div><span class="badge text-bg-light">{{ Str::headline($area->category) }}</span> <span class="badge {{ ['critical'=>'text-bg-danger','high'=>'text-bg-warning','moderate'=>'text-bg-success'][$area->severity] ?? 'text-bg-secondary' }}">{{ Str::headline($area->severity) }}</span></div><span class="badge {{ $area->status === 'active' ? 'text-bg-danger' : 'text-bg-secondary' }}">{{ Str::headline($area->status) }}</span></div>
            <h2 class="h4 mt-3 mb-1">{{ $area->location }}</h2><p class="text-success fw-semibold mb-2">{{ $area->affected_area }}</p><p class="text-secondary">{{ $area->summary }}</p>
            <div class="d-flex flex-wrap gap-2 align-items-center"> <a class="btn btn-sm btn-outline-success" href="{{ route('alerts.show', $area) }}">Situation details</a>@if($area->latitude && $area->longitude)<a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $area->latitude }}&mlon={{ $area->longitude }}#map=11/{{ $area->latitude }}/{{ $area->longitude }}">View on OpenStreetMap</a>@endif @auth @if(auth()->user()->role === 'admin')<a class="btn btn-sm btn-outline-primary" href="{{ route('alerts.edit', $area) }}">Update</a>@endif @endauth</div>
        </div></article></div>
    @empty <div class="col-12"><div class="alert alert-light border">No active affected areas match these filters.</div></div> @endforelse
    </div>
    <div class="mt-4">{{ $areas->links() }}</div>
</main>
@endsection
