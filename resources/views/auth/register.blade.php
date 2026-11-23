<x-guest-layout>
    <div class="auth-header">
        <h1 class="auth-title">Create Account</h1>
        <p class="auth-subtitle">Register a Tasker account to manage tasks.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="glass-form">
        @csrf

        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Katarina Ivanov" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            <label for="username" class="form-label">Username <span class="optional-tag">(Optional)</span></label>
            <x-text-input id="username" type="text" name="username" :value="old('username')" autocomplete="username" placeholder="kat.ivanov" />
            <x-input-error :messages="$errors->get('username')" />
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="you@example.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="form-actions auth-actions">
            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
            <a href="{{ route('login') }}" class="btn-secondary">Already registered?</a>
        </div>
    </form>
</x-guest-layout>
