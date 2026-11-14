<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display every task ordered chronologically by scheduled date.
     */
    public function index(): View
    {
        $tasks = Task::orderedByDate()->get();

        return view('tasks', [
            'title' => 'All Tasks',
            'tasks' => $tasks,
        ]);
    }
}
