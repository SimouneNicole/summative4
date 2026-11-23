<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Display non-archived tasks scheduled for today's date.
     */
    public function index(): View
    {
        $today = now();
        $tasks = Task::active()->forToday()->orderBy('id')->get();

        return view('welcome', [
            'title' => 'Tasks for Today',
            'todayDate' => $today->format('l, F j, Y'),
            'tasks' => $tasks,
        ]);
    }
}
