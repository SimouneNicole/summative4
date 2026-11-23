<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $navItems = [
            ['routeName' => 'welcome', 'match' => 'welcome', 'label' => 'Welcome'],
            ['routeName' => 'tasks.index', 'match' => 'tasks.*', 'label' => 'Tasks'],
            ['routeName' => 'profile.index', 'match' => 'profile.*', 'label' => 'Profile'],
            ['routeName' => 'about', 'match' => 'about', 'label' => 'About'],
        ];
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Tasks for Today' }} | Tasker</title>
    <meta name="description" content="Tasks for Today Management System built with Laravel Eloquent ORM, Breeze authentication, and completion email notifications.">
    <link rel="icon" type="image/svg+xml" href="{{ preg_replace('~^https?:~', '', asset('favicon.svg')) }}">
    <link rel="stylesheet" href="{{ preg_replace('~^https?:~', '', asset('css/style.css')) }}">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <div class="site-canvas">
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
                    @foreach ($navItems as $item)
                        @php $isCurrent = request()->routeIs($item['match']); @endphp
                        <a href="{{ route($item['routeName']) }}"
                           class="nav-item {{ $isCurrent ? 'is-active' : '' }}"
                           @if($isCurrent) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
                <div class="nav-divider" aria-hidden="true"></div>
                <div class="nav-auth">
                    @auth
                        <a href="{{ route('account.profile.edit') }}" class="nav-user-pill" title="Account Settings">
                            {{ Auth::user()->full_name ?: Auth::user()->username }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                            @csrf
                            <button type="submit" class="nav-item nav-logout-btn">Log out</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="nav-item {{ request()->routeIs('login') ? 'is-active' : '' }}">Log in</a>
                        <a href="{{ route('register') }}" class="nav-item nav-register-pill {{ request()->routeIs('register') ? 'is-active' : '' }}">Register</a>
                    @endauth
                </div>
            </nav>
        </header>

        <main id="main-content" class="content-stage" tabindex="-1">
            @include('partials.flash')
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>

        <footer class="site-footer">
            <span>Tasks for Today Management System</span>
            <span>Simoune Nicole &middot; IT0049 TSA4</span>
        </footer>
    </div>
</body>
</html>
