@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="welcome-heading">
    <div class="page-header">
        <div>
            <h1 id="welcome-heading" class="page-title">Tasks for Today</h1>
            <p class="page-subtitle">Agenda scheduled for {{ $todayDate }}.</p>
        </div>
        <span class="header-meta">{{ $tasks->count() }} {{ $tasks->count() === 1 ? 'task' : 'tasks' }}</span>
    </div>

    <div class="glass-panel">
        @if ($tasks->isEmpty())
            <div class="empty-state">
                <p class="empty-state-title">No tasks scheduled for today.</p>
                <p class="empty-state-body">All active work for today is clear. Check upcoming and past items in the complete schedule.</p>
                <a href="{{ route('tasks.index') }}" class="text-link">Browse all scheduled tasks</a>
            </div>
        @else
            <ul class="agenda-list">
                @foreach ($tasks as $task)
                    <li class="agenda-row">
                        <div class="agenda-main">
                            <span class="agenda-title">{{ $task->title }}</span>
                            <span class="agenda-date">{{ $task->task_date->format('Y-m-d') }}</span>
                        </div>
                        <x-status-badge :status="$task->status" />
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="section-actions">
        <a href="{{ route('tasks.index') }}" class="btn-primary">View All Tasks</a>
    </div>
</section>
@endsection
