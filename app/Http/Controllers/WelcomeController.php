<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Display tasks scheduled for today's date.
     */
    public function index(): View
    {
        $today = now();
        $tasks = Task::forToday()->orderBy('id')->get();

        return view('welcome', [
            'title' => 'Tasks for Today',
            'activePage' => 'welcome',
            'todayDate' => $today->format('l, F j, Y'),
            'tasks' => $tasks,
        ]);
    }
}
