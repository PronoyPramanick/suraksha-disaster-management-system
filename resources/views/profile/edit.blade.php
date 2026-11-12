<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="container-xl py-5 px-3 px-sm-4 px-lg-5">
        <div class="d-grid gap-3">
            <div class="card shadow-sm"><div class="card-body p-3 p-sm-4">
                <div class="profile-form-content">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div></div>

            <div class="card shadow-sm"><div class="card-body p-3 p-sm-4">
                <div class="profile-form-content">
                    @include('profile.partials.update-password-form')
                </div>
            </div></div>

            <div class="card shadow-sm"><div class="card-body p-3 p-sm-4">
                <div class="profile-form-content">
                    @include('profile.partials.delete-user-form')
                </div>
            </div></div>
        </div>
    </div>
</x-app-layout>
