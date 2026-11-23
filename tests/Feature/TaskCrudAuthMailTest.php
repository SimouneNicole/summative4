<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Mail\TaskCompletedMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TaskCrudAuthMailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_pages_are_accessible_without_authentication(): void
    {
        $this->get('/')->assertOk();
        $this->get('/tasks')->assertOk();
        $this->get('/profile')->assertOk();
        $this->get('/about')->assertOk();
    }

    public function test_task_management_routes_require_authentication(): void
    {
        $task = Task::query()->firstOrFail();

        $this->get('/tasks/create')->assertRedirect('/login');
        $this->post('/tasks', [
            'title' => 'Guest Task',
            'status' => TaskStatus::Pending->value,
            'task_date' => now()->toDateString(),
        ])->assertRedirect('/login');

        $this->get("/tasks/{$task->id}/edit")->assertRedirect('/login');
        $this->put("/tasks/{$task->id}", [
            'title' => 'Updated by Guest',
            'status' => TaskStatus::Completed->value,
            'task_date' => now()->toDateString(),
        ])->assertRedirect('/login');

        $this->delete("/tasks/{$task->id}")->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_task_and_invalid_input_preserves_old_input(): void
    {
        $user = User::query()->firstOrFail();

        $invalid = $this->actingAs($user)
            ->followingRedirects()
            ->from('/tasks/create')
            ->post('/tasks', [
                'title' => '',
                'status' => 'invalid-status',
                'task_date' => 'not-a-date',
            ]);

        $invalid->assertOk();
        $invalid->assertSee('id="form-error-summary"', false);
        $invalid->assertSee('Task title is required.');
        $invalid->assertSee('value="not-a-date"', false);

        $valid = $this->actingAs($user)->post('/tasks', [
            'title' => '  Prepare TSA4 Demo  ',
            'status' => TaskStatus::Ongoing->value,
            'task_date' => now()->toDateString(),
        ]);

        $valid->assertRedirect('/tasks');
        $valid->assertSessionHas('success', 'Task created successfully.');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Prepare TSA4 Demo',
            'status' => TaskStatus::Ongoing->value,
            'is_archived' => false,
        ]);
    }

    public function test_authenticated_user_can_soft_archive_task_and_hide_it_from_views(): void
    {
        $user = User::query()->firstOrFail();
        $task = Task::forToday()->where('title', 'Project deployment')->firstOrFail();

        $response = $this->actingAs($user)->delete("/tasks/{$task->id}");

        $response->assertRedirect('/tasks');
        $response->assertSessionHas('success', 'Task archived successfully.');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_archived' => true,
        ]);

        $this->get('/tasks')->assertDontSee($task->title);
        $this->get('/')->assertDontSee($task->title);
        $this->actingAs($user)->get("/tasks/{$task->id}/edit")->assertNotFound();
    }

    public function test_task_completed_email_sends_only_when_status_transitions_to_completed(): void
    {
        Mail::fake();

        $user = User::query()->firstOrFail();
        $task = Task::query()
            ->where('status', '!=', TaskStatus::Completed->value)
            ->firstOrFail();

        // Transitioning from pending/ongoing -> completed sends 1 notification email
        $firstUpdate = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => $task->title,
            'status' => TaskStatus::Completed->value,
            'task_date' => $task->task_date->format('Y-m-d'),
        ]);

        $firstUpdate->assertRedirect('/tasks');
        $firstUpdate->assertSessionHas('success', 'Task updated and completion notification email sent.');

        Mail::assertSent(TaskCompletedMail::class, 1);
        Mail::assertSent(TaskCompletedMail::class, function (TaskCompletedMail $mail) use ($task) {
            return $mail->hasTo(config('services.tasker.notification_email'))
                && $mail->task->is($task);
        });

        // Re-updating an already completed task must NOT send another email
        $secondUpdate = $this->actingAs($user)->put("/tasks/{$task->id}", [
            'title' => $task->title.' (Edited)',
            'status' => TaskStatus::Completed->value,
            'task_date' => $task->task_date->format('Y-m-d'),
        ]);

        $secondUpdate->assertRedirect('/tasks');
        $secondUpdate->assertSessionHas('success', 'Task updated successfully.');

        Mail::assertSent(TaskCompletedMail::class, 1);
    }

    public function test_task_completed_mailable_renders_task_details(): void
    {
        $task = Task::query()->firstOrFail();
        $task->status = TaskStatus::Completed;

        $mailable = new TaskCompletedMail($task, 'Katarina Ivanov');

        $mailable->assertSeeInHtml($task->title);
        $mailable->assertSeeInHtml('Completed');
        $mailable->assertSeeInHtml('Katarina Ivanov');
    }
}
