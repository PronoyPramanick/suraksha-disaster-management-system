<x-guest-layout>
    @php($isVolunteerLogin = $loginType === 'volunteer')
    @php($isAdminLogin = $loginType === 'admin')

    <main class="auth-shell auth-reference-layout">
        <section class="auth-story" aria-label="Suraksha disaster response platform">
            <img class="auth-reference-background" src="{{ asset('images/suraksha-login-hero.png') }}" alt="" aria-hidden="true">
            <div class="hero-copy">
                <div class="hero-brand">
                    <span class="hero-shield" aria-hidden="true"><svg viewBox="0 0 64 72" fill="none"><path d="M32 3 59 14v19c0 17-10 28-27 36C15 61 5 50 5 33V14L32 3Z" stroke="currentColor" stroke-width="3"/><path d="M17 36c5-8 10-8 15 0s10 8 15 0M17 44c5-8 10-8 15 0s10 8 15 0M25 23l7-8 7 8v8H25v-8Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 52c6-5 12-5 18 0" stroke="#28c69b" stroke-width="4" stroke-linecap="round"/></svg></span>
                    <span><strong>SURAKSHA</strong><small>Disaster Management System<br>in Bangladesh</small></span>
                </div>
                <h1>Smarter <em>Preparedness.</em><br>Faster <em>Response.</em><br>Safer <em>Communities.</em></h1>
                <p>SURAKSHA is a web-based platform to provide real-time disaster information, support rescue coordination, manage relief resources and build a safer Bangladesh.</p>
                <div class="hero-services" aria-label="Platform services">
                    <div><span class="service-icon">&#9638;</span><span>Disaster News<br>&amp; Alerts</span></div>
                    <div><span class="service-icon">&#10010;</span><span>Rescue &amp;<br>Relief</span></div>
                    <div><span class="service-icon">&#8962;</span><span>Shelter<br>Management</span></div>
                    <div><span class="service-icon">&#9822;</span><span>Volunteer<br>Network</span></div>
                </div>
            </div>
            <div class="hero-location"><span aria-hidden="true">&#9679;</span> Bangladesh &mdash; Stronger Together</div>
        </section>

        <section class="auth-panel" aria-labelledby="login-title">
            <div class="mobile-brand"><span class="mobile-shield" aria-hidden="true">&#8962;</span><span><strong>SURAKSHA</strong><small>Disaster Management System in Bangladesh</small></span></div>
            <div class="login-card">
                <h2 id="login-title">{{ $isVolunteerLogin ? 'Volunteer Login' : ($isAdminLogin ? 'Administrator Login' : 'Welcome Back') }}</h2>
                <p class="login-subtitle">{{ $isVolunteerLogin ? 'Sign in with your email, approved Volunteer ID, and password.' : ($isAdminLogin ? 'Sign in to manage disaster response and coordinate community support.' : 'Login to your citizen account to access the disaster management system.') }}</p>
                <x-auth-session-status class="auth-status" :status="session('status')" />

                <form method="POST" action="{{ route('login', ['type' => $loginType]) }}" class="login-form">
                    @csrf
                    <input type="hidden" name="login_type" value="{{ $loginType }}">

                    <div class="field-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrap">
                            <svg class="field-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.5" y="4" width="15" height="12" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="m3.5 5 6.5 5 6.5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" required autofocus autocomplete="username" aria-describedby="email-error">
                        </div>
                        <x-input-error id="email-error" :messages="$errors->get('email')" class="field-error" />
                    </div>

                    @if($isVolunteerLogin)
                        <div class="field-group">
                            <label for="volunteer_id">Volunteer ID</label>
                            <div class="input-wrap">
                                <svg class="field-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="2.5" y="3.5" width="15" height="13" rx="2" stroke="currentColor" stroke-width="1.6"/><circle cx="7" cy="8" r="1.8" stroke="currentColor" stroke-width="1.5"/><path d="M4.5 13a2.5 2.5 0 0 1 5 0m2-5h3m-3 3h3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
                                <input id="volunteer_id" type="text" name="volunteer_id" value="{{ old('volunteer_id') }}" placeholder="e.g. SUR-VOL-000123" required maxlength="100" autocomplete="off" aria-describedby="volunteer-id-error">
                            </div>
                            <x-input-error id="volunteer-id-error" :messages="$errors->get('volunteer_id')" class="field-error" />
                        </div>
                    @endif

                    <div class="field-group">
                        <label for="password">Password</label>
                        <div class="input-wrap" x-data="{ show: false }">
                            <svg class="field-icon" viewBox="0 0 20 20" fill="none" aria-hidden="true"><rect x="3.5" y="8.5" width="13" height="9" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M6.5 8.5V6a3.5 3.5 0 1 1 7 0v2.5M10 12v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                            <input id="password" x-bind:type="show ? 'text' : 'password'" name="password" placeholder="Enter your password" required autocomplete="current-password" aria-describedby="password-error">
                            <button class="password-toggle" type="button" x-on:click="show = !show" x-bind:aria-label="show ? 'Hide password' : 'Show password'" x-text="show ? 'Hide' : 'Show'"></button>
                        </div>
                        <x-input-error id="password-error" :messages="$errors->get('password')" class="field-error" />
                    </div>

                    <div class="login-options">
                        <label class="remember-row" for="remember_me"><input id="remember_me" type="checkbox" name="remember"><span>Remember me</span></label>
                        @if (Route::has('password.request'))<a href="{{ route('password.request') }}">Forgot password?</a>@endif
                    </div>
                    <button class="sign-in-button" type="submit"><svg viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M11 3h5a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-5M9 6l4 4-4 4m4-4H3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg><span>{{ $isVolunteerLogin ? 'Volunteer Login' : ($isAdminLogin ? 'Admin Login' : 'Citizen Login') }}</span></button>
                </form>

                @if (Route::has('register') && !$isAdminLogin)
                    <div class="form-divider"><span></span>OR<span></span></div>
                    <a class="create-account" href="{{ route('register') }}"><svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0H3Z"/></svg>Create a New Account</a>
                @endif

                <div class="role-grid" aria-label="Choose sign-in type">
                    <a href="{{ route('login', ['type' => 'citizen']) }}" class="role-grid-link {{ !$isVolunteerLogin && !$isAdminLogin ? 'active' : '' }}"><span class="role-icon citizen">&#9823;</span><strong>Citizen</strong><small>Get information<br>and request help</small></a>
                    <a href="{{ route('login', ['type' => 'volunteer']) }}" class="role-grid-link {{ $isVolunteerLogin ? 'active' : '' }}"><span class="role-icon volunteer">&#9823;&#9823;</span><strong>Volunteer</strong><small>Join and support<br>relief efforts</small></a>
                    <a href="{{ route('login', ['type' => 'admin']) }}" class="role-grid-link {{ $isAdminLogin ? 'active' : '' }}"><span class="role-icon admin">&#10070;</span><strong>Administrator</strong><small>Manage and<br>coordinate</small></a>
                </div>
            </div>
            <footer class="panel-footer"><span>&copy; {{ date('Y') }} Suraksha</span><span>Bangladesh &mdash; Stronger Together</span></footer>
        </section>
    </main>
</x-guest-layout>
