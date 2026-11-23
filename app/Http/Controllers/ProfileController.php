<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the public user profile page (authenticated user or canonical seeded profile).
     */
    public function index(Request $request): View
    {
        return view('profile', [
            'title' => 'Profile',
            'user' => $request->user() ?? User::query()->first(),
        ]);
    }

    /**
     * Display the authenticated user's account settings form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'title' => 'Account Settings',
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('account.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the authenticated user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
