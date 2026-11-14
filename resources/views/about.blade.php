@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="about-heading">
    <div class="page-header">
        <div>
            <h1 id="about-heading" class="page-title">About Tasks for Today</h1>
            <p class="page-subtitle">IT0049 Technical Summative Assessment 3 &mdash; Laravel Eloquent rebuild.</p>
        </div>
    </div>

    <div class="glass-panel prose-panel">
        <p>
            <strong>Tasks for Today Management System</strong> was developed by <strong>Simoune Nicole</strong>.
        </p>
        <p class="prose-muted">
            Rebuilt from the TSA1 CodeIgniter foundation into Laravel 13 using schema migrations,
            database seeders, Eloquent models with local date query scopes, and Blade views backed
            by a restrained Liquid Glass interface.
        </p>
    </div>
</section>
@endsection
