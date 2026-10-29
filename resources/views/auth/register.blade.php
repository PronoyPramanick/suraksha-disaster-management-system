<x-guest-layout>
    <main class="auth-shell auth-register-layout">
        <section class="auth-story" aria-label="Suraksha community response">
            <div class="register-hero-copy">
                <div class="register-hero-brand"><span class="register-shield" aria-hidden="true"><svg viewBox="0 0 64 72" fill="none"><path d="M32 3 59 14v19c0 17-10 28-27 36C15 61 5 50 5 33V14L32 3Z" stroke="currentColor" stroke-width="3"/><path d="M17 36c5-8 10-8 15 0s10 8 15 0M17 44c5-8 10-8 15 0s10 8 15 0M25 23l7-8 7 8v8H25v-8Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 52c6-5 12-5 18 0" stroke="#28c69b" stroke-width="4" stroke-linecap="round"/></svg></span><span><strong>SURAKSHA</strong><small>Disaster Management System<br>in Bangladesh</small></span></div>
                <p>Together for a safer,<br>stronger Bangladesh.</p>
            </div>
            <div class="register-hero-location">Bangladesh — Stronger Together</div>
            <div class="sr-only">Join SURAKSHA and be part of a safer Bangladesh. Choose Citizen or Volunteer registration.</div>
        </section>

        <section class="auth-panel" aria-labelledby="register-title">
            <div class="mobile-brand"><span class="mobile-shield" aria-hidden="true">⌂</span><span><strong>SURAKSHA</strong><small>Disaster Management System in Bangladesh</small></span></div>
            <div class="register-card">
                <header class="register-heading">
                    <span class="register-avatar" aria-hidden="true"><svg viewBox="0 0 28 28" fill="none"><circle cx="14" cy="8.5" r="5" stroke="currentColor" stroke-width="2"/><path d="M4.5 25v-3.2A6.8 6.8 0 0 1 11.3 15h5.4a6.8 6.8 0 0 1 6.8 6.8V25H4.5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg></span>
                    <div><h1 id="register-title">Create New Account</h1><p>Join SURAKSHA and be a part of a safer Bangladesh.</p></div>
                </header>

                <form method="POST" action="{{ route('register') }}" class="register-form" x-data="{ role: @js(old('role', 'citizen')) }">
                    @csrf
                    <div class="register-field">
                        <label for="name">Full Name</label>
                        <div class="register-input-wrap"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><circle cx="10" cy="6" r="3.2" stroke="currentColor" stroke-width="1.6"/><path d="M3.5 17v-1.2a4 4 0 0 1 4-4h5a4 4 0 0 1 4 4V17h-13Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg><input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required autofocus autocomplete="name" aria-describedby="name-error"></div>
                        <x-input-error id="name-error" :messages="$errors->get('name')" class="field-error" />
                    </div>

                    <div class="register-field">
                        <label for="email">Email Address</label>
                        <div class="register-input-wrap"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.5" y="4" width="15" height="12" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="m3.5 5 6.5 5 6.5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg><input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autocomplete="username" aria-describedby="email-error"></div>
                        <x-input-error id="email-error" :messages="$errors->get('email')" class="field-error" />
                    </div>

                    <div class="register-field">
                        <label for="phone">Phone Number</label>
                        <div class="register-input-wrap"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="m6.2 2.8 2.2 3.8-1.6 1.7a12 12 0 0 0 4.9 4.9l1.7-1.6 3.8 2.2-.7 3a1.5 1.5 0 0 1-1.8 1.1C8 16.4 3.6 12 2.1 5.3A1.5 1.5 0 0 1 3.2 3.5l3-.7Z" fill="currentColor"/></svg><input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" required autocomplete="tel" aria-describedby="phone-error"></div>
                        <x-input-error id="phone-error" :messages="$errors->get('phone')" class="field-error" />
                    </div>

                    <div class="register-field">
                        <label for="password">Password</label>
                        <div class="register-input-wrap" x-data="{ show: false }"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3.5" y="8.5" width="13" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M6.5 8.5V6a3.5 3.5 0 1 1 7 0v2.5M10 12v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg><input id="password" x-bind:type="show ? 'text' : 'password'" name="password" placeholder="Create a password" required autocomplete="new-password" aria-describedby="password-error"><button type="button" x-on:click="show = !show" x-bind:aria-label="show ? 'Hide password' : 'Show password'" x-text="show ? 'Hide' : 'Show'"></button></div>
                        <x-input-error id="password-error" :messages="$errors->get('password')" class="field-error" />
                    </div>

                    <div class="register-field">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="register-input-wrap" x-data="{ show: false }"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3.5" y="8.5" width="13" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M6.5 8.5V6a3.5 3.5 0 1 1 7 0v2.5M10 12v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg><input id="password_confirmation" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" placeholder="Confirm your password" required autocomplete="new-password"><button type="button" x-on:click="show = !show" x-bind:aria-label="show ? 'Hide password' : 'Show password'" x-text="show ? 'Hide' : 'Show'"></button></div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="field-error" />
                    </div>

                    <fieldset class="role-fieldset">
                        <legend>Register As</legend>
                        <div class="role-choice-grid">
                            <label class="role-choice" x-bind:class="role === 'citizen' ? 'selected' : ''">
                                <input type="radio" name="role" value="citizen" x-model="role" @checked(old('role', 'citizen') === 'citizen')>
                                <span class="choice-radio"></span><span class="choice-avatar citizen"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="7" r="4" fill="currentColor"/><path d="M4 21v-2a8 8 0 0 1 16 0v2H4Z" fill="currentColor"/></svg></span><strong>Citizen</strong><small>Get information<br>and request help</small>
                            </label>
                            <label class="role-choice" x-bind:class="role === 'volunteer' ? 'selected' : ''">
                                <input type="radio" name="role" value="volunteer" x-model="role" @checked(old('role') === 'volunteer')>
                                <span class="choice-radio"></span><span class="choice-avatar volunteer"><svg viewBox="0 0 28 24" aria-hidden="true"><circle cx="9" cy="7" r="4" fill="currentColor"/><circle cx="20" cy="8" r="3.5" fill="currentColor"/><path d="M1 22v-2a8 8 0 0 1 16 0v2H1ZM16 15a6.5 6.5 0 0 1 10 5.5V22h-7v-2a9.9 9.9 0 0 0-3-5Z" fill="currentColor"/></svg></span><strong>Volunteer</strong><small>Join and support<br>relief efforts</small>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="field-error" />
                    </fieldset>

                    <div class="volunteer-id-field" x-show="role === 'volunteer'" x-cloak>
                        <label for="volunteer_id">Volunteer ID</label>
                        <div class="register-input-wrap"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.5" y="4" width="15" height="12" rx="1.5" stroke="currentColor" stroke-width="1.5"/><circle cx="7" cy="9" r="1.6" stroke="currentColor" stroke-width="1.4"/><path d="M4.7 13a2.3 2.3 0 0 1 4.6 0M11 8h4M11 11h4M11 14h2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg><input id="volunteer_id" type="text" name="volunteer_id" value="{{ old('volunteer_id') }}" placeholder="Enter your volunteer ID" x-bind:required="role === 'volunteer'" x-bind:disabled="role !== 'volunteer'" aria-describedby="volunteer-id-help volunteer-id-error"><span class="volunteer-info" title="Use the ID assigned by your volunteer organization">ⓘ</span></div>
                        <p id="volunteer-id-help" class="volunteer-help">Required for volunteer registration.</p>
                        <x-input-error id="volunteer-id-error" :messages="$errors->get('volunteer_id')" class="field-error" />
                    </div>

                    <button class="register-submit" type="submit"><svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M7 9a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM1 18a6 6 0 0 1 12 0H1Zm14-9h2v3h3v2h-3v3h-2v-3h-3v-2h3V9Z"/></svg>Create Account</button>
                </form>
                <p class="register-login-link">Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
            <footer class="panel-footer"><span>© {{ date('Y') }} Suraksha</span><span>Bangladesh — Stronger Together</span></footer>
        </section>
    </main>
</x-guest-layout>
