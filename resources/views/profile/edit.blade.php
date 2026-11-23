@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="account-settings-heading">
    <div class="page-header">
        <div>
            <h1 id="account-settings-heading" class="page-title">Account Settings</h1>
            <p class="page-subtitle">Manage your profile information, password, and account access.</p>
        </div>
        <a href="{{ route('profile.index') }}" class="btn-secondary">&larr; Public Profile</a>
    </div>

    <div class="settings-stack">
        <div class="glass-panel glass-form-panel">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="glass-panel glass-form-panel">
            @include('profile.partials.update-password-form')
        </div>

        <div class="glass-panel glass-form-panel">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</section>
@endsection
