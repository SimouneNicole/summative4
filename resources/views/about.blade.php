@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="about-heading">
    <div class="page-header">
        <div>
            <h1 id="about-heading" class="page-title">About Tasks for Today</h1>
            <p class="page-subtitle">IT0049 Technical Summative Assessment 4 &mdash; Full CRUD, Auth &amp; Email Notifications.</p>
        </div>
    </div>

    <div class="glass-panel prose-panel">
        <p>
            <strong>Tasks for Today Management System</strong> was developed by <strong>Simoune Nicole</strong>.
        </p>
        <p class="prose-muted">
            Extended from TSA3 into a full-CRUD Laravel 13 application featuring Form Request validation,
            soft-archiving via <code>is_archived</code>, Laravel Breeze session authentication protecting task management actions,
            and automatic <code>TaskCompletedMail</code> notifications dispatched via Laravel's <code>Mail</code> facade when a task is completed.
        </p>
    </div>
</section>
@endsection
