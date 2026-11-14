<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the `tasks` table with reusable date query scopes.
 */
#[Fillable(['title', 'status', 'task_date'])]
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
        ];
    }

    /**
     * Scope a query to tasks scheduled for the current calendar date.
     */
    #[Scope]
    protected function forToday(Builder $query): void
    {
        // Uses whereDate so SQLite Y-m-d 00:00:00 and MySQL DATE values match identically.
        $query->whereDate('task_date', now()->toDateString());
    }

    /**
     * Scope a query to return all tasks ordered chronologically by date and ID.
     */
    #[Scope]
    protected function orderedByDate(Builder $query): void
    {
        $query->orderBy('task_date', 'asc')->orderBy('id', 'asc');
    }
}
