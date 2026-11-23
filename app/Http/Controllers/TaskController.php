<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\SaveTaskRequest;
use App\Mail\TaskCompletedMail;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display all non-archived tasks ordered chronologically by scheduled date.
     */
    public function index(): View
    {
        $tasks = Task::active()->orderedByDate()->get();

        return view('tasks', [
            'title' => 'All Tasks',
            'tasks' => $tasks,
        ]);
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        return view('tasks.create', [
            'title' => 'New Task',
            'task' => new Task([
                'status' => TaskStatus::Pending,
                'task_date' => now()->toDateString(),
            ]),
            'statuses' => TaskStatus::cases(),
        ]);
    }

    /**
     * Validate and store a newly created task.
     */
    public function store(SaveTaskRequest $request): RedirectResponse
    {
        Task::query()->create([
            ...$request->validated(),
            'is_archived' => false,
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing an existing non-archived task.
     */
    public function edit(Task $task): View
    {
        abort_if($task->is_archived, 404);

        return view('tasks.edit', [
            'title' => 'Edit Task',
            'task' => $task,
            'statuses' => TaskStatus::cases(),
        ]);
    }

    /**
     * Validate and update an existing task, emailing when status transitions to completed.
     */
    public function update(SaveTaskRequest $request, Task $task): RedirectResponse
    {
        abort_if($task->is_archived, 404);

        $wasCompleted = $task->status === TaskStatus::Completed;

        $task->update($request->validated());
        $task->refresh();

        $mailSent = false;
        if (! $wasCompleted && $task->status === TaskStatus::Completed) {
            $recipient = (string) config('services.tasker.notification_email', 'katarina.ivanov@gmail.com');
            Mail::to($recipient)->send(new TaskCompletedMail(
                $task,
                $request->user()?->full_name ?? $request->user()?->username,
            ));
            $mailSent = true;
        }

        $message = $mailSent
            ? 'Task updated and completion notification email sent.'
            : 'Task updated successfully.';

        return redirect()
            ->route('tasks.index')
            ->with('success', $message);
    }

    /**
     * Soft-delete a task by marking it archived instead of removing the row.
     */
    public function destroy(Task $task): RedirectResponse
    {
        abort_if($task->is_archived, 404);

        $task->update(['is_archived' => true]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task archived successfully.');
    }
}
