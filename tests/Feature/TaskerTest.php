<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskerTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_is_idempotent_and_populates_required_user_and_task_dates(): void
    {
        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('users', 1);
        $this->assertSame(1, User::query()->count());
        $this->assertSame(9, Task::query()->count());
        $this->assertGreaterThanOrEqual(8, Task::query()->count());

        $distinctDates = Task::query()->distinct()->count('task_date');
        $this->assertGreaterThanOrEqual(3, $distinctDates);
        $this->assertGreaterThanOrEqual(1, Task::forToday()->count());
    }

    public function test_eloquent_scopes_filter_today_and_order_even_when_inserted_out_of_order(): void
    {
        Task::query()->create([
            'title' => 'Future task added first',
            'status' => TaskStatus::Pending,
            'task_date' => now()->addDays(2)->toDateString(),
        ]);

        Task::query()->create([
            'title' => 'Today task added second',
            'status' => TaskStatus::Ongoing,
            'task_date' => now()->toDateString(),
        ]);

        Task::query()->create([
            'title' => 'Past task added third',
            'status' => TaskStatus::Completed,
            'task_date' => now()->subDay()->toDateString(),
        ]);

        $todayTasks = Task::forToday()->get();
        $this->assertCount(1, $todayTasks);
        $this->assertSame('Today task added second', $todayTasks->first()->title);

        $orderedTitles = Task::orderedByDate()->pluck('title')->all();
        $this->assertSame([
            'Past task added third',
            'Today task added second',
            'Future task added first',
        ], $orderedTitles);
    }

    public function test_task_status_defaults_to_pending_and_username_is_unique(): void
    {
        $task = Task::query()->create([
            'title' => 'Verify default status constraint',
            'task_date' => now()->toDateString(),
        ]);

        $freshTask = Task::query()->findOrFail($task->id);
        $this->assertSame(TaskStatus::Pending, $freshTask->status);

        User::query()->create([
            'username' => 'Kat.1na',
            'full_name' => 'Katarina Ivanov',
            'email' => 'katarina.ivanov@gmail.com',
        ]);

        $this->expectException(QueryException::class);

        User::query()->create([
            'username' => 'Kat.1na',
            'full_name' => 'Duplicate Account',
            'email' => 'duplicate@example.com',
        ]);
    }

    public function test_welcome_page_displays_only_todays_tasks(): void
    {
        $this->seed();

        $response = $this->get(route('welcome'));

        $response->assertOk();
        $response->assertSee('Tasks for Today');
        $response->assertSee('Meeting');
        $response->assertSee('Project deployment');
        $response->assertSee('Review Investor Feedback');
        $response->assertDontSee('Prepare Quarterly Reports');
        $response->assertDontSee('Meeting with Marketing');
        $response->assertDontSee('Finalize client onboarding checklist');
    }

    public function test_welcome_page_handles_empty_today_schedule(): void
    {
        Task::query()->create([
            'title' => 'Completed yesterday item',
            'status' => TaskStatus::Completed,
            'task_date' => now()->subDay()->toDateString(),
        ]);

        $response = $this->get(route('welcome'));

        $response->assertOk();
        $response->assertSee('No tasks scheduled for today.');
        $response->assertDontSee('Completed yesterday item');
    }

    public function test_tasks_page_displays_all_tasks_ordered_chronologically(): void
    {
        $this->seed();

        $response = $this->get(route('tasks.index'));

        $response->assertOk();
        $response->assertSee('All Tasks');
        $response->assertSeeInOrder([
            'Prepare Quarterly Reports',
            'Pitch Deck',
            'Meeting',
            'Review Investor Feedback',
            'Meeting with Marketing',
            'Finalize client onboarding checklist',
        ]);
    }

    public function test_profile_page_displays_single_seeded_user_and_empty_fallback(): void
    {
        $emptyResponse = $this->get(route('profile.index'));
        $emptyResponse->assertOk();
        $emptyResponse->assertSee('No user profile found.');

        $this->seed();

        $response = $this->get(route('profile.index'));
        $response->assertOk();
        $response->assertSee('Kat.1na');
        $response->assertSee('Katarina Ivanov');
        $response->assertSee('katarina.ivanov@gmail.com');
    }

    public function test_about_page_identifies_simoune_as_developer(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('About Tasks for Today');
        $response->assertSee('Simoune Nicole');
        $response->assertSee('aria-current="page"', false);
    }
}
