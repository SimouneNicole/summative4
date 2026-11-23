<x-guest-layout>
    <div class="auth-header">
        <h1 class="auth-title">Log in</h1>
        <p class="auth-subtitle">Sign in to create, edit, or archive tasks.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="glass-form">
        @csrf

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="katarina.ivanov@gmail.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="auth-remember-row">
            <label for="remember_me" class="checkbox-label">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-link auth-inline-link" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="form-actions auth-actions">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
            <a href="{{ route('register') }}" class="btn-secondary">Create account</a>
        </div>
    </form>
</x-guest-layout>
