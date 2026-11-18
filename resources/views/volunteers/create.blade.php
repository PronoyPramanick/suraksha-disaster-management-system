<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="map-eyebrow">COMMUNITY RESPONSE</span>
                <h1 class="h3 mb-0 mt-2">Volunteer Application</h1>
            </div>
            <a class="btn btn-outline-secondary" href="{{ route('volunteers.index') }}">My Applications</a>
        </div>
    </x-slot>

    <div class="container-xl py-4">
        <div class="row g-4 align-items-start">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm volunteer-form-card">
                    <div class="card-body p-4 p-lg-5">
                        <p class="text-secondary mb-4">Share your skills and availability. An administrator will review your application before volunteer access is granted.</p>

                        <form method="POST" action="{{ route('volunteers.store') }}" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label class="form-label" for="name">Full name</label>
                                <input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required maxlength="255" autocomplete="name">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label" for="phone">Mobile number</label>
                                <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" type="tel" value="{{ old('phone', auth()->user()->phone) }}" required maxlength="30" autocomplete="tel">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-7">
                                <label class="form-label" for="email">Email address</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}" required maxlength="255" autocomplete="email">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-5">
                                <label class="form-label" for="blood_group">Blood group</label>
                                <select class="form-select @error('blood_group') is-invalid @enderror" id="blood_group" name="blood_group" required>
                                    <option value="" disabled @selected(!old('blood_group'))>Select blood group</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', 'Unknown'] as $bloodGroup)
                                        <option value="{{ $bloodGroup }}" @selected(old('blood_group') === $bloodGroup)>{{ $bloodGroup === 'Unknown' ? 'Prefer not to say / Unknown' : $bloodGroup }}</option>
                                    @endforeach
                                </select>
                                @error('blood_group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="address">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2" required maxlength="1000" autocomplete="street-address" placeholder="Your current address">{{ old('address') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="skills">Skills</label>
                                <textarea class="form-control @error('skills') is-invalid @enderror" id="skills" name="skills" rows="4" required maxlength="2000" placeholder="For example: first aid, logistics, driving, communications">{{ old('skills') }}</textarea>
                                @error('skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="availability">Availability</label>
                                <input class="form-control @error('availability') is-invalid @enderror" id="availability" name="availability" value="{{ old('availability') }}" required maxlength="255" placeholder="Days, hours, or emergency availability">
                                @error('availability')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="experience">Relevant experience <span class="text-secondary fw-normal">(optional)</span></label>
                                <textarea class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience" rows="4" maxlength="2000" placeholder="Describe relevant volunteering, training, or work experience">{{ old('experience') }}</textarea>
                                @error('experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 d-flex flex-wrap align-items-center gap-3 pt-2">
                                <button class="btn btn-success px-4" type="submit">Submit application</button>
                                <span class="small text-secondary">Your account remains a citizen account until approval.</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <aside class="col-lg-4 d-grid gap-3" aria-label="Volunteer application information">
                <section class="card border-0 shadow-sm volunteer-info-card">
                    <div class="card-body p-4">
                        <div class="volunteer-info-heading">
                            <span class="volunteer-info-icon">01</span>
                            <div><span class="volunteer-info-kicker">APPLICATION PROCESS</span><h2 class="h5 mb-0 mt-1">What happens next?</h2></div>
                        </div>
                        <ol class="volunteer-steps mb-0">
                            <li><strong>Submit</strong><span>Share your contact details, skills, and availability.</span></li>
                            <li><strong>Review</strong><span>An administrator reviews your application.</span></li>
                            <li><strong>Decision</strong><span>Approved applicants receive volunteer access. Rejected applications remain citizen accounts.</span></li>
                        </ol>
                    </div>
                </section>

                <section class="card border-0 shadow-sm volunteer-info-card">
                    <div class="card-body p-4">
                        <div class="volunteer-info-heading">
                            <span class="volunteer-info-icon">02</span>
                            <div><span class="volunteer-info-kicker">HELPFUL TIPS</span><h2 class="h5 mb-0 mt-1">Make it clear</h2></div>
                        </div>
                        <ul class="volunteer-tips mb-0">
                            <li>List practical skills such as first aid, logistics, translation, or driving.</li>
                            <li>State when you are usually available and whether you can respond during emergencies.</li>
                            <li>Mention relevant training or experience. The experience field is optional.</li>
                            <li>Select “Unknown” if you do not know your blood group.</li>
                        </ul>
                    </div>
                </section>

                <div class="volunteer-status-note d-flex align-items-start gap-3">
                    <span class="volunteer-info-icon volunteer-info-icon-small">03</span>
                    <span>After submitting, open <a href="{{ route('volunteers.index') }}">My Applications</a> to check your application status.</span>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
