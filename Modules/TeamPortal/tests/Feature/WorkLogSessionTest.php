<?php

namespace Modules\TeamPortal\Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Modules\TeamPortal\Models\WorkLog;
use Modules\ProjectManagement\Models\Task;
use Modules\ProjectManagement\Models\TimeLog;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkLogSessionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_developer_can_check_in_with_valid_parameters()
    {
        $user = User::factory()->create(['role' => 'developer']);
        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/v1/team/work-logs/check-in', [
            'planned_projects' => [1, 2],
            'planned_tasks'    => ['Task 1', 'Task 2'],
            'resources_needed' => 'Dev laptop'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('work_logs', [
            'user_id' => $user->id,
            'status'  => 'in_progress',
        ]);
    }

    public function test_completed_task_checkout_requires_evidence()
    {
        $user = User::factory()->create(['role' => 'developer']);
        $log = WorkLog::create([
            'user_id'          => $user->id,
            'start_time'       => now(),
            'planned_tasks'    => 'Task 1',
            'planned_projects' => [1],
            'status'           => 'in_progress',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->putJson("/api/v1/team/work-logs/{$log->id}/check-out", [
            'actual_tasks' => 'Finished task 1',
            'tasks_data'   => [
                [
                    'task_id'  => 101,
                    'result'   => 'Completed',
                    'evidence' => null // Trigger error
                ]
            ]
        ]);

        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'Evidence is required for completed task (ID: 101).']);
    }

    public function test_partial_task_requires_evidence_after_three_consecutive_partials()
    {
        $user = User::factory()->create(['role' => 'developer']);
        Sanctum::actingAs($user, ['*']);

        // Create 2 historical partial logs without evidence
        for ($i = 0; $i < 2; $i++) {
            WorkLog::create([
                'user_id'          => $user->id,
                'start_time'       => now()->subDays($i + 1),
                'end_time'         => now()->subDays($i + 1)->addHour(),
                'planned_tasks'    => 'Task 1',
                'planned_projects' => [1],
                'actual_tasks'     => 'Progressing',
                'status'           => 'completed',
                'tasks_data'       => [
                    [
                        'task_id'  => 101,
                        'result'   => 'Partial',
                        'evidence' => null
                    ]
                ]
            ]);
        }

        // 3rd log (current session check-out)
        $currentLog = WorkLog::create([
            'user_id'          => $user->id,
            'start_time'       => now(),
            'planned_tasks'    => 'Task 1',
            'planned_projects' => [1],
            'status'           => 'in_progress',
        ]);

        // Trying to checkout 3rd time as Partial without evidence should fail
        $response = $this->putJson("/api/v1/team/work-logs/{$currentLog->id}/check-out", [
            'actual_tasks' => 'Still working',
            'tasks_data'   => [
                [
                    'task_id'  => 101,
                    'result'   => 'Partial',
                    'evidence' => null
                ]
            ]
        ]);

        $response->assertStatus(400);
        $response->assertJsonFragment(['error' => 'Evidence is required for task (ID: 101) as it has been partial for 3+ consecutive sessions.']);
    }

    public function test_time_calculation_summed_from_pm_time_logs()
    {
        $user = User::factory()->create(['role' => 'developer']);
        $currentLog = WorkLog::create([
            'user_id'          => $user->id,
            'start_time'       => now()->subMinutes(120),
            'planned_tasks'    => 'Task 1',
            'planned_projects' => [1],
            'status'           => 'in_progress',
        ]);

        // Add matching TimeLogs
        TimeLog::create([
            'task_id'          => 101,
            'user_id'          => $user->id,
            'user_type'        => $user->getMorphClass(),
            'start_time'       => now()->subMinutes(60),
            'end_time'         => now()->subMinutes(30),
            'duration_minutes' => 30,
        ]);

        TimeLog::create([
            'task_id'          => 101,
            'user_id'          => $user->id,
            'user_type'        => $user->getMorphClass(),
            'start_time'       => now()->subMinutes(20),
            'end_time'         => now()->subMinutes(10),
            'duration_minutes' => 10,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->putJson("/api/v1/team/work-logs/{$currentLog->id}/check-out", [
            'actual_tasks' => 'Worked on task 101',
            'tasks_data'   => [
                [
                    'task_id'  => 101,
                    'result'   => 'Partial',
                    'evidence' => 'https://example.com'
                ]
            ]
        ]);

        $response->assertStatus(200);
        $this->assertEquals(40, $response->json('data.tasks_data.0.time_spent_minutes'));
    }
}
