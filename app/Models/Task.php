<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the `tasks` table with active/date scopes and soft-archive support.
 */
#[Fillable(['title', 'status', 'task_date', 'is_archived'])]
class Task extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'task_date' => 'date',
            'is_archived' => 'boolean',
        ];
    }

    /**
     * Scope a query to exclude soft-archived tasks.
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query->where('is_archived', false);
    }

    /**
     * Scope a query to tasks scheduled for the current calendar date.
     */
    #[Scope]
    protected function forToday(Builder $query): void
    {
        $query->whereDate('task_date', now()->toDateString());
    }

    /**
     * Scope a query to return tasks ordered chronologically by date and ID.
     */
    #[Scope]
    protected function orderedByDate(Builder $query): void
    {
        $query->orderBy('task_date', 'asc')->orderBy('id', 'asc');
    }
}
