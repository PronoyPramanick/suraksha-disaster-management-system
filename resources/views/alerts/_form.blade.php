<form class="disaster-alert-form" method="POST" action="{{ $action }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="alert-form-field"><label for="title">Title</label><input id="title" name="title" type="text" value="{{ old('title', $alert->title) }}" maxlength="180" required>@error('title')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
    <div class="alert-form-grid">
        <div class="alert-form-field"><label for="kind">Update type</label><select id="kind" name="kind" required>@foreach (['alert' => 'Disaster alert', 'news' => 'Community news'] as $value => $label)<option value="{{ $value }}" @selected(old('kind', $alert->kind ?: 'alert') === $value)>{{ $label }}</option>@endforeach</select>@error('kind')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
        <div class="alert-form-field"><label for="category">Disaster type</label><select id="category" name="category" required>@foreach (['general' => 'General', 'cyclone' => 'Cyclone', 'flood' => 'Flood', 'storm' => 'Storm', 'river_level' => 'River Level', 'shelter' => 'Shelter', 'health' => 'Health', 'rescue' => 'Rescue', 'fire' => 'Fire', 'earthquake' => 'Earthquake', 'landslide' => 'Landslide'] as $value => $label)<option value="{{ $value }}" @selected(old('category', $alert->category ?: 'general') === $value)>{{ $label }}</option>@endforeach</select>@error('category')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
        <div class="alert-form-field"><label for="severity">Severity</label><select id="severity" name="severity" required>@foreach (['critical' => 'Critical', 'high' => 'High', 'moderate' => 'Moderate'] as $value => $label)<option value="{{ $value }}" @selected(old('severity', $alert->severity ?: 'moderate') === $value)>{{ $label }}</option>@endforeach</select>@error('severity')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
        <div class="alert-form-field"><label for="location">District</label><input id="location" name="location" type="text" value="{{ old('location', $alert->location) }}" maxlength="160" placeholder="e.g. Cox's Bazar" required>@error('location')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
        <div class="alert-form-field"><label for="affected_area">Affected Area</label><input id="affected_area" name="affected_area" type="text" value="{{ old('affected_area', $alert->affected_area) }}" maxlength="180" placeholder="e.g. Teknaf coastal upazilas" required>@error('affected_area')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
        <div class="alert-form-field"><label for="status">Alert Status</label><select id="status" name="status" required>@foreach (['active' => 'Active', 'resolved' => 'Resolved'] as $value => $label)<option value="{{ $value }}" @selected(old('status', $alert->status ?: 'active') === $value)>{{ $label }}</option>@endforeach</select>@error('status')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
        <div class="alert-form-field"><label for="latitude">Latitude <span class="form-optional">(optional)</span></label><input id="latitude" name="latitude" type="number" step="0.0000001" min="-90" max="90" value="{{ old('latitude', $alert->latitude) }}" placeholder="e.g. 21.4272">@error('latitude')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
        <div class="alert-form-field"><label for="longitude">Longitude <span class="form-optional">(optional)</span></label><input id="longitude" name="longitude" type="number" step="0.0000001" min="-180" max="180" value="{{ old('longitude', $alert->longitude) }}" placeholder="e.g. 92.0058">@error('longitude')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
    </div>
    <div class="alert-form-field alert-coordinate-picker-field"><label for="alert-location-picker">Pin alert location on the map <span class="form-optional">(optional)</span></label><div id="alert-location-picker" class="alert-coordinate-picker" aria-label="Click the map to set alert coordinates"></div><p class="alert-form-hint">Click the map to place or move the marker. Coordinates fill in automatically. Map data © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap contributors</a>.</p><button class="alert-clear-coordinate" type="button" id="clear-alert-coordinates">Clear selected location</button></div>
    <div class="alert-form-field"><label for="summary">Short summary</label><input id="summary" name="summary" type="text" value="{{ old('summary', $alert->summary) }}" maxlength="280" placeholder="A brief overview shown in the alert list" required>@error('summary')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
    <div class="alert-form-field"><label for="body">Full update</label><textarea id="body" name="body" rows="8" maxlength="20000" placeholder="Include what happened, who is affected, and what people should do." required>{{ old('body', $alert->body) }}</textarea>@error('body')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
    <div class="alert-form-field"><label for="safety_instructions">Safety Instructions</label><textarea id="safety_instructions" name="safety_instructions" rows="6" maxlength="12000" placeholder="List clear steps people should take to stay safe." required>{{ old('safety_instructions', $alert->safety_instructions) }}</textarea>@error('safety_instructions')<span class="alert-form-error">{{ $message }}</span>@enderror</div>
    <div class="alert-form-actions"><a href="{{ $editing ? route('alerts.show', $alert) : route('alerts.index') }}">Cancel</a><button type="submit">{{ $editing ? 'Save changes' : 'Publish update' }}</button></div>
</form>

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const picker = document.getElementById('alert-location-picker');
        if (!picker || !window.L) return;

        const latitude = document.getElementById('latitude');
        const longitude = document.getElementById('longitude');
        const hasLocation = latitude.value !== '' && longitude.value !== '';
        const map = L.map(picker).setView(
            hasLocation ? [Number(latitude.value), Number(longitude.value)] : [23.685, 90.3563],
            hasLocation ? 12 : 7,
        );
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap contributors</a>',
        }).addTo(map);

        let marker = hasLocation ? L.marker([Number(latitude.value), Number(longitude.value)]).addTo(map) : null;
        map.on('click', event => {
            const lat = event.latlng.lat.toFixed(7);
            const lng = event.latlng.lng.toFixed(7);
            latitude.value = lat;
            longitude.value = lng;
            latitude.dispatchEvent(new Event('input', { bubbles: true }));
            longitude.dispatchEvent(new Event('input', { bubbles: true }));
            if (marker) marker.setLatLng(event.latlng);
            else marker = L.marker(event.latlng).addTo(map);
        });

        document.getElementById('clear-alert-coordinates').addEventListener('click', () => {
            latitude.value = '';
            longitude.value = '';
            marker?.remove();
            marker = null;
        });

        setTimeout(() => map.invalidateSize(), 100);
    });
    </script>
@endonce
