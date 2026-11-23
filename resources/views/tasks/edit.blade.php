@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="edit-task-heading">
    <div class="page-header">
        <div>
            <h1 id="edit-task-heading" class="page-title">{{ $title }}</h1>
            <p class="page-subtitle">Update details or mark task #{{ $task->id }} as completed.</p>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn-secondary">&larr; Back to Tasks</a>
    </div>

    @include('partials.form_errors')

    @include('tasks._form', [
        'action' => route('tasks.update', $task),
        'method' => 'PUT',
        'mode' => 'edit',
        'task' => $task,
        'statuses' => $statuses,
    ])
</section>
@endsection
