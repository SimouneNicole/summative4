@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="tasks-heading">
    <div class="page-header">
        <div>
            <h1 id="tasks-heading" class="page-title">All Tasks</h1>
            <p class="page-subtitle">Complete task schedule ordered chronologically by scheduled date.</p>
        </div>
        <span class="header-meta">{{ $tasks->count() }} {{ \Illuminate\Support\Str::plural('task', $tasks->count()) }}</span>
    </div>

    <div class="glass-panel">
        @if ($tasks->isEmpty())
            <div class="empty-state">
                <p class="empty-state-title">No tasks recorded.</p>
                <p class="empty-state-body">Run database migrations and seeders to populate the task schedule.</p>
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
