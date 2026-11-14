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
        return view('profile', [
            'title' => 'Profile',
            'user' => User::query()->first(),
        ]);
    }
}
