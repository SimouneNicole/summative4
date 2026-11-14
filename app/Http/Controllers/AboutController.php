<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Display the static About page identifying the developer.
     */
    public function index(): View
    {
        return view('about', [
            'title' => 'About',
            'activePage' => 'about',
        ]);
    }
}
