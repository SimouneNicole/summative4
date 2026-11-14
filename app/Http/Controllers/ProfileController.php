<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the single persisted user profile record.
     */
    public function index(): View
    {
        $user = User::query()->first();

        return view('profile', [
            'title' => 'Profile',
            'activePage' => 'profile',
            'user' => $user,
        ]);
    }
}
