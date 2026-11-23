<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to submit this form.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize whitespace and default blank status before validation runs.
     */
    protected function prepareForValidation(): void
    {
        $status = trim((string) $this->input('status', ''));

        $this->merge([
            'title' => trim((string) $this->input('title', '')),
            'status' => $status === '' ? TaskStatus::Pending->value : $status,
            'task_date' => trim((string) $this->input('task_date', '')),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'task_date' => ['required', 'date'],
        ];
    }

    /**
     * Get clear validation messages displayed in the task form summary and inline fields.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required.',
            'title.max' => 'Task title must not exceed 255 characters.',
            'status.required' => 'Please select a task status.',
            'status.enum' => 'Please choose a valid workflow status.',
            'task_date.required' => 'Scheduled date is required.',
            'task_date.date' => 'Please enter a valid scheduled date.',
        ];
    }
}
