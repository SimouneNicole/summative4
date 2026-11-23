@extends('layouts.app')

@section('content')
<section class="page-section" aria-labelledby="create-task-heading">
    <div class="page-header">
        <div>
            <h1 id="create-task-heading" class="page-title">{{ $title }}</h1>
            <p class="page-subtitle">Schedule a new task item with workflow status.</p>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn-secondary">&larr; Back to Tasks</a>
    </div>

    @include('partials.form_errors')

    @include('tasks._form', [
        'action' => route('tasks.store'),
        'method' => 'POST',
        'mode' => 'create',
        'task' => $task,
        'statuses' => $statuses,
    ])
</section>
@endsection
