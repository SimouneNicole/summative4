<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z0-9._-]+$/', 'unique:'.User::class],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $username = $this->resolveUniqueUsername(
            $request->string('username')->trim()->toString(),
            $request->string('name')->trim()->toString(),
            $request->string('email')->trim()->toString(),
        );

        $user = User::create([
            'username' => $username,
            'full_name' => $request->string('name')->trim()->toString(),
            'email' => $request->string('email')->lower()->trim()->toString(),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Resolve a unique username when registering via Breeze's standard form.
     */
    private function resolveUniqueUsername(string $provided, string $name, string $email): string
    {
        if ($provided !== '') {
            return $provided;
        }

        $base = Str::of($name !== '' ? $name : Str::before($email, '@'))
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9._-]+/', '.')
            ->trim('.')
            ->limit(40, '')
            ->toString();

        if ($base === '') {
            $base = 'user';
        }

        $candidate = $base;
        $suffix = 1;
        while (User::query()->where('username', $candidate)->exists()) {
            $candidate = Str::limit($base, 44, '').'.'.$suffix;
            $suffix++;
        }

        return $candidate;
    }
}
