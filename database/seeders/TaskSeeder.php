<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Seed 9 tasks across 4 distinct dates (yesterday, today, tomorrow, +2 days) idempotently.
     */
    public function run(): void
    {
        $today = now()->startOfDay();

        $tasks = [
            [
                'title' => 'Prepare Quarterly Reports',
                'status' => TaskStatus::Pending,
                'task_date' => $today->copy()->subDay()->toDateString(),
            ],
            [
                'title' => 'Pitch Deck',
                'status' => TaskStatus::Pending,
                'task_date' => $today->copy()->subDay()->toDateString(),
            ],
            [
                'title' => 'Meeting',
                'status' => TaskStatus::Ongoing,
                'task_date' => $today->toDateString(),
            ],
            [
                'title' => 'Project deployment',
                'status' => TaskStatus::Completed,
                'task_date' => $today->toDateString(),
            ],
            [
                'title' => 'Review Investor Feedback',
                'status' => TaskStatus::Completed,
                'task_date' => $today->toDateString(),
            ],
            [
                'title' => 'Meeting with Marketing',
                'status' => TaskStatus::Pending,
                'task_date' => $today->copy()->addDay()->toDateString(),
            ],
            [
                'title' => 'Review the monthly budget',
                'status' => TaskStatus::Ongoing,
                'task_date' => $today->copy()->addDay()->toDateString(),
            ],
            [
                'title' => 'Finalize client onboarding checklist',
                'status' => TaskStatus::Pending,
                'task_date' => $today->copy()->addDays(2)->toDateString(),
            ],
            [
                'title' => 'Audit weekly team milestones',
                'status' => TaskStatus::Pending,
                'task_date' => $today->copy()->addDays(2)->toDateString(),
            ],
        ];

        foreach ($tasks as $item) {
            // Query with whereDate so repeated seeding remains idempotent on SQLite and MySQL.
            $exists = Task::query()
                ->where('title', $item['title'])
                ->whereDate('task_date', $item['task_date'])
                ->exists();

            if (! $exists) {
                Task::query()->create($item);
            }
        }
    }
}
