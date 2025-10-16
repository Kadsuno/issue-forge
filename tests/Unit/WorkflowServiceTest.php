<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use App\Models\WorkflowState;
use App\Services\WorkflowService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class WorkflowServiceTest extends TestCase
{
    use RefreshDatabase;

    private WorkflowService $workflowService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->workflowService = new WorkflowService;

        // Seed roles and permissions first
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        // Seed predefined states
        $this->workflowService->seedPredefinedStates();
    }

    public function test_gets_states_for_global_project(): void
    {
        $project = Project::factory()->create(['workflow_type' => 'global']);

        $states = $this->workflowService->getStatesForProject($project);

        $this->assertGreaterThanOrEqual(9, $states->count());
        $this->assertTrue($states->every(fn ($state) => $state->project_id === null));
    }

    public function test_gets_filtered_states_for_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('agent');

        $project = Project::factory()->create(['workflow_type' => 'global']);

        // Create a restricted state
        $restrictedState = WorkflowState::create([
            'name' => 'Restricted',
            'slug' => 'restricted',
            'label' => 'Restricted',
            'color' => 'red',
            'is_predefined' => false,
            'order' => 20,
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $restrictedState->roles()->attach($adminRole, ['can_set_to' => true]);

        $states = $this->workflowService->getStatesForUser($user, $project);

        $this->assertFalse($states->contains('id', $restrictedState->id));
    }

    public function test_can_transition_checks_permissions(): void
    {
        $user = User::factory()->create();
        $user->assignRole('agent');

        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'status' => 'open',
        ]);

        // Agent can transition to in_progress (no restrictions)
        $canTransition = $this->workflowService->canTransition($ticket, 'in_progress', $user);
        $this->assertTrue($canTransition);

        // Create restricted state
        $restrictedState = WorkflowState::create([
            'name' => 'Restricted',
            'slug' => 'restricted',
            'label' => 'Restricted',
            'color' => 'red',
            'is_predefined' => false,
            'order' => 20,
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $restrictedState->roles()->attach($adminRole, ['can_set_to' => true]);

        // Agent cannot transition to restricted state
        $cannotTransition = $this->workflowService->canTransition($ticket, 'restricted', $user);
        $this->assertFalse($cannotTransition);
    }

    public function test_transition_updates_ticket_and_logs_history(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'status' => 'open',
        ]);

        $this->actingAs($user);

        $this->workflowService->transition($ticket, 'in_progress', $user, 'Starting work');

        $ticket->refresh();

        $this->assertEquals('in_progress', $ticket->status);
        $this->assertEquals('open', $ticket->previous_status);

        $this->assertDatabaseHas('ticket_status_history', [
            'ticket_id' => $ticket->id,
            'from_status' => 'open',
            'to_status' => 'in_progress',
            'user_id' => $user->id,
            'comment' => 'Starting work',
        ]);
    }

    public function test_transition_throws_exception_without_permission(): void
    {
        $user = User::factory()->create();
        $user->assignRole('agent');

        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'status' => 'open',
        ]);

        // Create restricted state
        $restrictedState = WorkflowState::create([
            'name' => 'Restricted',
            'slug' => 'restricted',
            'label' => 'Restricted',
            'color' => 'red',
            'is_predefined' => false,
            'order' => 20,
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $restrictedState->roles()->attach($adminRole, ['can_set_to' => true]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User does not have permission to transition to this status.');

        $this->workflowService->transition($ticket, 'restricted', $user);
    }
}
