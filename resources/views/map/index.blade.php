@extends('layouts.map')

@section('content')
<main class="container-xl map-page py-4 py-lg-5">
    <div class="map-page-heading">
        <div>
            <span class="map-eyebrow"><span class="map-live-dot"></span> BANGLADESH · LIVE ALERTS</span>
            <h1>Disaster incident map</h1>
            <p>Explore active disaster alerts by location. Select a marker to see its summary and safety information.</p>
        </div>
    </div>

    <section class="map-card" aria-label="Active disaster alerts map">
        <div class="map-toolbar">
            <div class="map-filter-search"><label for="map-search">Search alerts</label><input id="map-search" class="form-control" type="search" placeholder="Search title, district or area"></div>
            <div class="map-filter-severity"><label for="map-severity">Severity</label><select id="map-severity" class="form-select"><option value="">All severities</option><option value="critical">Critical</option><option value="high">High</option><option value="moderate">Moderate</option></select></div>
            <div class="map-legend" aria-label="Marker severity legend">
                <span><i class="legend-critical"></i>Critical</span><span><i class="legend-high"></i>High</span><span><i class="legend-moderate"></i>Moderate</span>
            </div>
        </div>

        <div class="map-canvas-wrap">
            <div id="disaster-map" role="application" aria-label="Interactive map of Bangladesh showing active disaster alerts"></div>
            <div id="map-loading" class="map-loading" role="status">Loading active alertsâ€¦</div>
        </div>
        <div class="map-status-bar"><span id="map-result-count" aria-live="polite">Loading map dataâ€¦</span><span>Map tiles Â© <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap contributors</a></span></div>
    </section>

    <p class="map-data-note">Only alerts with coordinates appear on the map. Administrators can add coordinates when creating or editing an alert.</p>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const map = L.map('disaster-map', { scrollWheelZoom: false }).setView([23.6850, 90.3563], 7);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>',
    }).addTo(map);

    const markerLayer = L.featureGroup().addTo(map);
    const severityColors = { critical: '#c8353d', high: '#e67e22', moderate: '#d3a529' };
    const search = document.getElementById('map-search');
    const severity = document.getElementById('map-severity');
    const count = document.getElementById('map-result-count');
    const loading = document.getElementById('map-loading');
    let alerts = [];

    function popupContent(alert) {
        const card = document.createElement('div');
        card.className = 'map-popup';
        const title = document.createElement('strong');
        title.textContent = alert.title;
        const area = document.createElement('span');
        area.textContent = `${alert.location}${alert.affected_area ? ` Â· ${alert.affected_area}` : ''}`;
        const summary = document.createElement('p');
        summary.textContent = alert.summary;
        const badge = document.createElement('small');
        badge.className = `popup-severity severity-${alert.severity}`;
        badge.textContent = `${alert.severity} Â· ${alert.category.replaceAll('_', ' ')}`;
        const apiLink = document.createElement('a');
        apiLink.href = `/api/v1/alerts/${encodeURIComponent(alert.id)}`;
        apiLink.target = '_blank';
        apiLink.rel = 'noopener';
        apiLink.textContent = 'Alert details and safety instructions';
        card.append(title, area, summary, badge, apiLink);
        return card;
    }

    function renderMarkers() {
        markerLayer.clearLayers();
        const term = search.value.trim().toLocaleLowerCase();
        const selectedSeverity = severity.value;
        const visibleAlerts = alerts.filter(alert => {
            const searchable = `${alert.title} ${alert.location} ${alert.affected_area ?? ''} ${alert.summary}`.toLocaleLowerCase();
            return (!selectedSeverity || alert.severity === selectedSeverity) && (!term || searchable.includes(term));
        });

        visibleAlerts.forEach(alert => {
            const marker = L.circleMarker([alert.latitude, alert.longitude], {
                radius: 9,
                color: '#fff',
                weight: 2,
                fillColor: severityColors[alert.severity] ?? '#397d68',
                fillOpacity: 0.95,
            });
            marker.bindPopup(popupContent(alert));
            marker.addTo(markerLayer);
        });

        count.textContent = `${visibleAlerts.length} of ${alerts.length} mapped active alert${alerts.length === 1 ? '' : 's'}`;
    }

    search.addEventListener('input', renderMarkers);
    severity.addEventListener('change', renderMarkers);

    try {
        const response = await fetch('/api/v1/alerts?status=active&has_coordinates=1&per_page=100', {
            headers: { Accept: 'application/json' },
        });
        if (!response.ok) throw new Error('Could not load alert data.');
        const result = await response.json();
        alerts = result.data;
        loading.hidden = true;
        renderMarkers();
        if (markerLayer.getLayers().length) map.fitBounds(markerLayer.getBounds().pad(0.18), { maxZoom: 10 });
    } catch (error) {
        loading.textContent = 'Alert locations could not be loaded. Please refresh the page and try again.';
        count.textContent = 'Map data unavailable';
    }

    setTimeout(() => map.invalidateSize(), 100);
});
</script>
@endpush
