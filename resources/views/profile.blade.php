@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="profile-heading">
    <div class="page-header">
        <div>
            <h1 id="profile-heading" class="page-title">Profile</h1>
            <p class="page-subtitle">User account record retrieved from the database.</p>
        </div>
    </div>

    <div class="glass-panel">
        @if ($user === null)
            <div class="empty-state">
                <p class="empty-state-title">No user profile found.</p>
                <p class="empty-state-body">Seed the users table to display the registered account profile.</p>
            </div>
        @else
            <dl class="profile-ledger">
                <div class="ledger-row">
                    <dt class="ledger-label">User ID</dt>
                    <dd class="ledger-value tabular">{{ $user->id }}</dd>
                </div>
                <div class="ledger-row">
                    <dt class="ledger-label">Username</dt>
                    <dd class="ledger-value">{{ $user->username }}</dd>
                </div>
                <div class="ledger-row">
                    <dt class="ledger-label">Full Name</dt>
                    <dd class="ledger-value">{{ $user->full_name }}</dd>
                </div>
                <div class="ledger-row">
                    <dt class="ledger-label">Email Address</dt>
                    <dd class="ledger-value">{{ $user->email }}</dd>
                </div>
                <div class="ledger-row">
                    <dt class="ledger-label">Created At</dt>
                    <dd class="ledger-value tabular">{{ $user->created_at?->format('F j, Y g:i A') ?? 'Not recorded' }}</dd>
                </div>
            </dl>
        @endif
    </div>
</section>
@endsection
