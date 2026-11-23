@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="tasks-heading">
    <div class="page-header">
        <div>
            <h1 id="tasks-heading" class="page-title">All Tasks</h1>
            <p class="page-subtitle">Complete active task schedule ordered chronologically by scheduled date.</p>
        </div>
        <div class="header-actions-group">
            <span class="header-meta">{{ $tasks->count() }} {{ \Illuminate\Support\Str::plural('task', $tasks->count()) }}</span>
            @auth
                <a href="{{ route('tasks.create') }}" class="btn-primary">+ New Task</a>
            @else
                <a href="{{ route('login') }}" class="btn-secondary">Log in to manage tasks</a>
            @endauth
        </div>
    </div>

    <div class="glass-panel">
        @if ($tasks->isEmpty())
            <div class="empty-state">
                <p class="empty-state-title">No active tasks recorded.</p>
                <p class="empty-state-body">Create a new task or run database seeders to populate the task schedule.</p>
                @auth
                    <a href="{{ route('tasks.create') }}" class="text-link">Create your first task</a>
                @endauth
            </div>
        @else
            <div class="table-scroll">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th scope="col" class="col-id">#</th>
                            <th scope="col">Task</th>
                            <th scope="col">Scheduled Date</th>
                            <th scope="col">Status</th>
                            @auth
                                <th scope="col" class="col-actions">Actions</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="cell-id">{{ $task->id }}</td>
                                <td class="cell-primary cell-task">{{ $task->title }}</td>
                                <td class="cell-date">{{ $task->task_date->format('Y-m-d') }}</td>
                                <td class="cell-status">
                                    <x-status-badge :status="$task->status" />
                                </td>
                                @auth
                                    <td class="cell-actions">
                                        <div class="row-actions">
                                            <a href="{{ route('tasks.edit', $task) }}"
                                               class="table-action-link"
                                               aria-label="Edit {{ $task->title }}">Edit</a>
                                            <form action="{{ route('tasks.destroy', $task) }}"
                                                  method="POST"
                                                  class="inline-form"
                                                  onsubmit="return confirm('Archive this task?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="table-action-danger"
                                                        aria-label="Archive {{ $task->title }}">Archive</button>
                                            </form>
                                        </div>
                                    </td>
                                @endauth
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
