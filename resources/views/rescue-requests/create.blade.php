<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="map-eyebrow">EMERGENCY RESPONSE</span>
                <h1 class="h3 mb-0 mt-2">Request Rescue Assistance</h1>
            </div>
            <a class="btn btn-outline-secondary" href="{{ route('rescue-requests.index') }}">My Requests</a>
        </div>
    </x-slot>

    <div class="container-xl py-4">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10">
                <div class="rescue-request-intro mb-4">
                    <span class="rescue-alert-mark" aria-hidden="true">!</span>
                    <p class="mb-0">Share where help is needed and how many people need assistance. Your request will be reviewed by the response team.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <strong>Please correct the highlighted information.</strong>
                        <ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('rescue-requests.store') }}" class="card border-0 shadow-sm rescue-request-form">
                    @csrf
                    <div class="card-body p-4 p-lg-5">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="contact_name" class="form-label">Contact name</label>
                                <input id="contact_name" name="contact_name" required maxlength="120" value="{{ old('contact_name', auth()->user()->name) }}" class="form-control @error('contact_name') is-invalid @enderror" autocomplete="name">
                                @error('contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact_phone" class="form-label">Contact phone</label>
                                <input id="contact_phone" name="contact_phone" type="tel" required maxlength="30" value="{{ old('contact_phone', auth()->user()->phone) }}" class="form-control @error('contact_phone') is-invalid @enderror" autocomplete="tel">
                                @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="location" class="form-label">Location <span class="text-secondary fw-normal">(district, area, landmark)</span></label>
                                <input id="location" name="location" required maxlength="255" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="e.g. Companiganj, near the cyclone shelter" autocomplete="street-address">
                                @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="people_count" class="form-label">People needing assistance</label>
                                <input id="people_count" name="people_count" type="number" min="1" max="5000" required value="{{ old('people_count', 1) }}" class="form-control @error('people_count') is-invalid @enderror">
                                @error('people_count')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="urgency" class="form-label">Urgency</label>
                                <select id="urgency" name="urgency" required class="form-select @error('urgency') is-invalid @enderror">
                                    <option value="Critical" @selected(old('urgency') === 'Critical')>Critical</option>
                                    <option value="High" @selected(old('urgency', 'High') === 'High')>High</option>
                                    <option value="Moderate" @selected(old('urgency') === 'Moderate')>Moderate</option>
                                </select>
                                @error('urgency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Emergency description</label>
                                <textarea id="description" name="description" rows="5" maxlength="5000" required class="form-control @error('description') is-invalid @enderror" placeholder="Describe the situation, hazards, and any urgent medical needs.">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-3 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-danger btn-lg rescue-submit-button">Submit rescue request</button>
                            <a href="{{ route('rescue-requests.index') }}" class="btn btn-link text-secondary text-decoration-none">Cancel</a>
                            <span class="small text-secondary ms-md-auto">Please provide a phone number the team can reach.</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
