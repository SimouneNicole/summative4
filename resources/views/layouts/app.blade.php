<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $protocolRelative = static fn (string $path): string => preg_replace('~^https?:~', '', asset($path));
        $navItems = [
            ['key' => 'welcome', 'label' => 'Welcome', 'route' => route('welcome')],
            ['key' => 'tasks', 'label' => 'Tasks', 'route' => route('tasks.index')],
            ['key' => 'profile', 'label' => 'Profile', 'route' => route('profile.index')],
            ['key' => 'about', 'label' => 'About', 'route' => route('about')],
        ];
    @endphp
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Tasks for Today' }} | Tasker</title>
    <meta name="description" content="Tasks for Today Management System built with Laravel Eloquent ORM and migrations.">
    <link rel="icon" type="image/svg+xml" href="{{ $protocolRelative('favicon.svg') }}">
    <link rel="alternate icon" href="{{ $protocolRelative('favicon.ico') }}">
    <link rel="stylesheet" href="{{ $protocolRelative('css/style.css') }}">
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
                        @php $isCurrent = ($activePage ?? '') === $item['key']; @endphp
                        <a href="{{ $item['route'] }}"
                           class="nav-item {{ $isCurrent ? 'is-active' : '' }}"
                           @if($isCurrent) aria-current="page" @endif>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </nav>
        </header>

        <main id="main-content" class="content-stage" tabindex="-1">
            @yield('content')
        </main>

        <footer class="site-footer">
            <span>Tasks for Today Management System</span>
            <span>Simoune Nicole &middot; IT0049 TSA3</span>
        </footer>
    </div>
</body>
</html>
