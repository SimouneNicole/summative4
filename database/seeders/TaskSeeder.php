<?php

namespace Database\Seeders;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Seed 9 tasks across 4 distinct dates (yesterday, today, tomorrow, +2 days).
     */
    public function run(): void
    {
        $today = now()->startOfDay();

        $tasks = [
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

        foreach ($tasks as $task) {
            Task::query()->firstOrCreate(
                [
                    'title' => $task['title'],
                    'task_date' => $task['task_date'],
                ],
                [
                    'status' => $task['status'],
                ]
            );
        }
    }
}
