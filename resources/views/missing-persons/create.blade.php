<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="map-eyebrow">COMMUNITY SUPPORT</span>
                <h1 class="h3 mb-0 mt-2">{{ $reportType === 'found' ? 'Report a Found Person' : 'Report a Missing Person' }}</h1>
            </div>
            <a class="btn btn-outline-secondary" href="{{ route('missing-persons.index') }}">View reports</a>
        </div>
    </x-slot>

    <div class="container-xl py-4">
        <div class="row justify-content-center g-4">
            <div class="col-xl-8 col-lg-8">
                <div class="missing-report-type-switch mb-3" role="group" aria-label="Choose report type">
                    <a class="btn {{ $reportType === 'missing' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('missing-persons.create', ['type' => 'missing']) }}">Missing Person Report</a>
                    <a class="btn {{ $reportType === 'found' ? 'btn-success' : 'btn-outline-success' }}" href="{{ route('missing-persons.create', ['type' => 'found']) }}">Found a Person</a>
                </div>
                <div class="missing-report-intro {{ $reportType === 'found' ? 'found-report' : '' }} mb-4">
                    <span class="missing-report-icon" aria-hidden="true">{{ $reportType === 'found' ? '✓' : '!' }}</span>
                    <div>
                        <h2 class="h5 mb-1">{{ $reportType === 'found' ? 'Help reconnect someone with their family' : 'Share clear information to help locate them' }}</h2>
                        <p class="mb-0">{{ $reportType === 'found' ? 'Add where and when the person was found, plus a safe way for responders to contact you.' : 'Please include the last known location, date, and a safe contact method.' }}</p>
                    </div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>Please review the highlighted fields.</strong>
                        <ul class="mb-0 mt-2">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('missing-persons.store') }}" enctype="multipart/form-data" class="card border-0 shadow-sm missing-report-form">
                    @csrf
                    <input type="hidden" name="report_type" value="{{ $reportType }}">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label" for="name">{{ $reportType === 'found' ? "Person's name (if known)" : 'Full name' }}</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="{{ $reportType === 'found' ? 'Enter name, or write Unknown' : 'Enter the missing person’s full name' }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="age">Approximate age <span class="text-secondary fw-normal">(optional)</span></label>
                                <input class="form-control @error('age') is-invalid @enderror" id="age" name="age" type="number" min="0" max="120" value="{{ old('age') }}" placeholder="Age">
                                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label" for="last_known_location">{{ $reportType === 'found' ? 'Location found' : 'Last known location' }}</label>
                                <input class="form-control @error('last_known_location') is-invalid @enderror" id="last_known_location" name="last_known_location" value="{{ old('last_known_location') }}" required maxlength="255" autocomplete="street-address" placeholder="District, area, and a nearby landmark">
                                @error('last_known_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label" for="last_seen_date">{{ $reportType === 'found' ? 'Date found' : 'Date last seen' }}</label>
                                <input class="form-control @error('last_seen_date') is-invalid @enderror" id="last_seen_date" name="last_seen_date" type="date" max="{{ now()->toDateString() }}" value="{{ old('last_seen_date') }}" required>
                                @error('last_seen_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="contact_method">Safe contact method</label>
                                <input class="form-control @error('contact_method') is-invalid @enderror" id="contact_method" name="contact_method" value="{{ old('contact_method') }}" required maxlength="255" placeholder="Phone number or another safe way to reach you">
                                @error('contact_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="photo">Photograph <span class="text-secondary fw-normal">(optional)</span></label>
                                <div class="missing-photo-upload" id="missing-photo-upload">
                                    <div class="missing-photo-upload-heading">
                                        <span class="missing-photo-upload-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M12 16V4m0 0L7.5 8.5M12 4l4.5 4.5M5 14.5v4A1.5 1.5 0 0 0 6.5 20h11a1.5 1.5 0 0 0 1.5-1.5v-4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                                        <span class="missing-photo-upload-copy"><strong id="photo-file-name">Choose a JPG photo</strong><small id="photo-help">JPG or JPEG format · Maximum file size 2 MB</small></span>
                                    </div>
                                    <input class="form-control form-control-sm @error('photo') is-invalid @enderror" id="photo" type="file" accept=".jpg,.jpeg,image/jpeg" name="photo" aria-describedby="photo-help photo-error">
                                    <div class="missing-photo-client-error" id="photo-client-error" role="alert" hidden></div>
                                    <div class="missing-photo-preview" id="photo-preview" hidden><img id="photo-preview-image" alt="Selected photo preview"><span>Photo preview</span></div>
                                </div>
                                @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <span id="photo-error" class="visually-hidden">Upload a JPG or JPEG image no larger than 2 MB.</span>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="details">Additional details <span class="text-secondary fw-normal">(optional)</span></label>
                                <textarea class="form-control @error('details') is-invalid @enderror" id="details" name="details" rows="4" maxlength="3000" placeholder="Clothing, identifying details, or other helpful information">{{ old('details') }}</textarea>
                                @error('details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 d-flex flex-wrap align-items-center gap-3 pt-2 border-top">
                                <button class="btn {{ $reportType === 'found' ? 'btn-success' : 'btn-primary' }} btn-lg missing-report-submit" type="submit">{{ $reportType === 'found' ? 'Submit found person report' : 'Submit missing person report' }}</button>
                                <a class="btn btn-link text-secondary text-decoration-none" href="{{ route('missing-persons.index') }}">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <aside class="col-xl-3 col-lg-4">
                <div class="card border-0 shadow-sm missing-safety-card">
                    <div class="card-body p-4">
                        <span class="missing-safety-kicker">SAFETY FIRST</span>
                        <h2 class="h5 mt-2">{{ $reportType === 'found' ? 'Protect the person’s privacy' : 'Share information carefully' }}</h2>
                        <ul class="mb-0">
                            <li>{{ $reportType === 'found' ? 'Keep the person in a safe, public place while contacting trusted responders.' : 'Use a contact method that is safe for the family and the person.' }}</li>
                            <li>Do not post sensitive personal details in the additional information field.</li>
                            <li>Contact local emergency services if there is immediate danger.</li>
                        </ul>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('photo');
    const fileName = document.getElementById('photo-file-name');
    const preview = document.getElementById('photo-preview');
    const previewImage = document.getElementById('photo-preview-image');
    const clientError = document.getElementById('photo-client-error');
    let previewUrl = null;

    input.addEventListener('change', () => {
        const file = input.files[0];
        clientError.hidden = true;
        clientError.textContent = '';
        if (!file) {
            fileName.textContent = 'Choose a JPG photo';
            preview.hidden = true;
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = null;
            return;
        }

        const isJpeg = /\.jpe?g$/i.test(file.name);
        if (!isJpeg || file.size > 2 * 1024 * 1024) {
            clientError.textContent = !isJpeg
                ? 'Please select a JPG or JPEG image.'
                : 'The image must be 2 MB or smaller.';
            clientError.hidden = false;
            fileName.textContent = 'Choose a JPG photo';
            input.value = '';
            preview.hidden = true;
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = null;
            return;
        }

        fileName.textContent = file.name;
        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = URL.createObjectURL(file);
        previewImage.src = previewUrl;
        preview.hidden = false;
    });
});
</script>
@endpush
