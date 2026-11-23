<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Account Access' }} | Tasker</title>
    <link rel="icon" type="image/svg+xml" href="{{ preg_replace('~^https?:~', '', asset('favicon.svg')) }}">
    <link rel="stylesheet" href="{{ preg_replace('~^https?:~', '', asset('css/style.css')) }}">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <div class="site-canvas auth-canvas">
        <header class="nav-capsule-wrapper">
            <nav class="nav-capsule" aria-label="Primary Navigation">
                <a href="{{ route('welcome') }}" class="nav-brand">
                    <span class="brand-mark" aria-hidden="true">
                        <svg width="13" height="13" viewBox="0 0 16 16" fill="none">
                            <path d="M3.5 8.5L6.5 11.5L12.5 5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span>Tasker</span>
                </a>
                <div class="nav-divider" aria-hidden="true"></div>
                <div class="nav-links">
                    <a href="{{ route('welcome') }}" class="nav-item">Welcome</a>
                    <a href="{{ route('tasks.index') }}" class="nav-item">Tasks</a>
                    <a href="{{ route('login') }}" class="nav-item {{ request()->routeIs('login') ? 'is-active' : '' }}">Log in</a>
                    <a href="{{ route('register') }}" class="nav-item {{ request()->routeIs('register') ? 'is-active' : '' }}">Register</a>
                </div>
            </nav>
        </header>

        <main id="main-content" class="content-stage auth-stage" tabindex="-1">
            <div class="glass-panel glass-auth-card">
                {{ $slot }}
            </div>
        </main>

        <footer class="site-footer">
            <span>Tasks for Today Management System</span>
            <span>Simoune Nicole &middot; IT0049 TSA4</span>
        </footer>
    </div>
</body>
</html>
