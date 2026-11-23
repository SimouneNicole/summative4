@php
    $selectedStatus = old('status', $task->status?->value ?? \App\Enums\TaskStatus::Pending->value);
    $dateValue = old('task_date', $task->task_date?->format('Y-m-d') ?? now()->toDateString());
@endphp

<div class="glass-panel glass-form-panel">
    <form action="{{ $action }}" method="POST" class="glass-form">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
            <label for="title" class="form-label">
                Task Title <span class="req-mark" aria-hidden="true">*</span>
            </label>
            <input
                type="text"
                id="title"
                name="title"
                class="form-control"
                value="{{ old('title', $task->title) }}"
                maxlength="255"
                placeholder="e.g. Prepare Sprint Review Notes"
                required
                aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}"
                @error('title') aria-describedby="title_error" @enderror
            >
            @error('title')
                <p id="title_error" class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-row-two">
            <div class="form-group {{ $errors->has('task_date') ? 'has-error' : '' }}">
                <label for="task_date" class="form-label">
                    Scheduled Date <span class="req-mark" aria-hidden="true">*</span>
                </label>
                <input
                    type="date"
                    id="task_date"
                    name="task_date"
                    class="form-control"
                    value="{{ $dateValue }}"
                    required
                    aria-invalid="{{ $errors->has('task_date') ? 'true' : 'false' }}"
                    @error('task_date') aria-describedby="task_date_error" @enderror
                >
                @error('task_date')
                    <p id="task_date_error" class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" class="form-label">
                    Status <span class="req-mark" aria-hidden="true">*</span>
                </label>
                <select
                    id="status"
                    name="status"
                    class="form-control"
                    aria-invalid="{{ $errors->has('status') ? 'true' : 'false' }}"
                    @error('status') aria-describedby="status_error" @enderror
                >
                    @foreach ($statuses as $statusCase)
                        <option value="{{ $statusCase->value }}" {{ $selectedStatus === $statusCase->value ? 'selected' : '' }}>
                            {{ $statusCase === \App\Enums\TaskStatus::Completed ? 'Completed (Done)' : $statusCase->label() }}
                        </option>
                    @endforeach
                </select>
                <p class="field-help">Changing a task to <strong>Completed (Done)</strong> automatically sends a notification email.</p>
                @error('status')
                    <p id="status_error" class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                {{ $mode === 'create' ? 'Save Task' : 'Update Task' }}
            </button>
            <a href="{{ route('tasks.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
