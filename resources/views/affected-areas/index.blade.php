@extends('layouts.map', ['title' => 'Affected Areas'])

@section('content')
<main class="container-xl py-4 py-lg-5 affected-areas-page">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <span class="map-eyebrow"><span class="map-live-dot"></span> COMMUNITY SITUATION</span>
            <h1 class="mt-2 mb-2">Affected Areas</h1>
            <p class="text-secondary mb-0">Browse active disaster situations by district, disaster type, and severity.</p>
        </div>
        @auth
            @if(auth()->user()->role === 'admin')
                <a class="btn btn-success" href="{{ route('alerts.create') }}">Add affected area</a>
            @endif
        @endauth
    </div>

    <form class="affected-area-filter map-card mb-4" method="GET" action="{{ route('affected-areas.index') }}" aria-label="Filter affected areas">
        <div class="affected-area-filter-grid">
            <div class="affected-area-search">
                <label for="area-search">Search area</label>
                <input id="area-search" class="form-control" name="q" value="{{ $search }}" placeholder="Search district, area, or situation" type="search">
            </div>
            <div>
                <label for="area-category">Disaster type</label>
                <select id="area-category" class="form-select" name="category">
                    <option value="">All disaster types</option>
                    @foreach(['cyclone','flood','storm','river_level','fire','earthquake','landslide','general'] as $item)
                        <option value="{{ $item }}" @selected($category === $item)>{{ Str::headline($item) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="area-severity">Severity</label>
                <select id="area-severity" class="form-select" name="severity">
                    <option value="">All severity levels</option>
                    @foreach(['critical','high','moderate'] as $item)
                        <option value="{{ $item }}" @selected($severity === $item)>{{ Str::headline($item) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="affected-area-filter-actions">
                <button class="btn btn-success" type="submit">Apply filters</button>
                <a class="btn btn-outline-secondary" href="{{ route('affected-areas.index') }}">Clear</a>
            </div>
        </div>
    </form>

    <section class="map-card affected-area-map-card mb-4" aria-label="Map of affected areas">
        <div class="affected-area-map-heading">
            <div>
                <h2 class="h5 mb-1">Affected locations map</h2>
                <p class="mb-0">Map markers follow your search and filters.</p>
            </div>
            <div class="affected-area-legend" aria-label="Severity legend">
                <span><i class="area-marker-critical"></i>Critical</span>
                <span><i class="area-marker-high"></i>High</span>
                <span><i class="area-marker-moderate"></i>Moderate</span>
            </div>
        </div>
        <div class="map-canvas-wrap">
            <div id="affected-areas-map" role="application" aria-label="Interactive map showing filtered affected areas"></div>
            <div class="affected-map-empty" id="affected-map-empty" @if($mapAreas->isNotEmpty()) hidden @endif>No affected areas with map coordinates match these filters.</div>
        </div>
        <div class="map-status-bar">
            <span>{{ $mapAreas->count() }} mapped location{{ $mapAreas->count() === 1 ? '' : 's' }} for the current filters</span>
            <span>Map tiles &copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap contributors</a></span>
        </div>
    </section>

    <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
        <h2 class="h4 mb-0">Situation list</h2>
        <span class="small text-secondary">{{ $areas->total() }} matching area{{ $areas->total() === 1 ? '' : 's' }}</span>
    </div>

    <div class="row g-3">
        @forelse($areas as $area)
            <div class="col-lg-6">
                <article class="card h-100 border-0 shadow-sm affected-area-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge text-bg-light">{{ Str::headline($area->category) }}</span>
                                <span class="badge {{ ['critical'=>'text-bg-danger','high'=>'text-bg-warning','moderate'=>'text-bg-success'][$area->severity] ?? 'text-bg-secondary' }}">{{ Str::headline($area->severity) }} severity</span>
                            </div>
                            <span class="badge text-bg-danger">Active</span>
                        </div>
                        <h3 class="h5 mt-3 mb-1">{{ $area->location }}</h3>
                        <p class="text-success fw-semibold mb-2">{{ $area->affected_area }}</p>
                        <p class="text-secondary mb-4">{{ $area->summary }}</p>
                        <div class="d-flex flex-wrap gap-2 align-items-center mt-auto">
                            <a class="btn btn-sm btn-outline-success" href="{{ route('alerts.show', $area) }}">Situation details</a>
                            @if($area->latitude !== null && $area->longitude !== null)
                                <a class="btn btn-sm btn-outline-secondary" target="_blank" rel="noopener" href="https://www.openstreetmap.org/?mlat={{ $area->latitude }}&mlon={{ $area->longitude }}#map=11/{{ $area->latitude }}/{{ $area->longitude }}">Open in OpenStreetMap</a>
                            @endif
                            @auth
                                @if(auth()->user()->role === 'admin')
                                    <a class="btn btn-sm btn-outline-primary ms-lg-auto" href="{{ route('alerts.edit', $area) }}">Update area</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="affected-area-empty-state">
                    <span class="affected-area-empty-mark" aria-hidden="true">⌖</span>
                    <div>
                        <h3 class="h5 mb-1">{{ $search === '' && !$category && !$severity ? 'No active affected areas yet' : 'No areas match these filters' }}</h3>
                        <p class="text-secondary mb-0">{{ $search === '' && !$category && !$severity ? 'Published active disaster alerts will appear here and on the map.' : 'Try changing or clearing your filters to see more affected areas.' }}</p>
                    </div>
                    @auth
                        @if(auth()->user()->role === 'admin')
                            <a class="btn btn-success ms-lg-auto" href="{{ route('alerts.create') }}">Add an affected area</a>
                        @endif
                    @endauth
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $areas->links() }}</div>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const areas = @js($mapAreas);
    if (!window.L) {
        const mapEmpty = document.getElementById('affected-map-empty');
        mapEmpty.hidden = false;
        mapEmpty.textContent = 'Map tiles could not load. You can still browse affected areas in the list.';
        return;
    }

    const map = L.map('affected-areas-map', { scrollWheelZoom: false }).setView([23.685, 90.3563], 7);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>',
    }).addTo(map);

    const colors = { critical: '#c8353d', high: '#e67e22', moderate: '#d3a529' };
    const markers = L.featureGroup().addTo(map);

    areas.forEach(area => {
        const marker = L.circleMarker([area.latitude, area.longitude], {
            radius: 9,
            color: '#fff',
            weight: 2,
            fillColor: colors[area.severity] || '#397d68',
            fillOpacity: 0.95,
        });

        const popup = document.createElement('div');
        popup.className = 'map-popup';
        const title = document.createElement('strong');
        title.textContent = area.location;
        const affectedArea = document.createElement('span');
        affectedArea.textContent = area.affected_area || '';
        const summary = document.createElement('p');
        summary.textContent = area.summary;
        const severity = document.createElement('small');
        severity.className = `popup-severity severity-${area.severity}`;
        severity.textContent = `${area.severity} · ${area.category.replaceAll('_', ' ')}`;
        const details = document.createElement('a');
        details.href = `/alerts/${encodeURIComponent(area.id)}`;
        details.textContent = 'Situation details';
        popup.append(title, affectedArea, summary, severity, details);
        marker.bindPopup(popup).addTo(markers);
    });

    if (markers.getLayers().length) {
        map.fitBounds(markers.getBounds().pad(0.18), { maxZoom: 10 });
    }

    setTimeout(() => map.invalidateSize(), 100);
});
</script>
@endpush
