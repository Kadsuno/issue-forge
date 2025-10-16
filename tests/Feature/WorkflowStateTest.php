<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Models\WorkflowState;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

final class WorkflowStateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'project_manager']);
        Role::create(['name' => 'agent']);

        // Seed workflow states
        $workflowService = new \App\Services\WorkflowService;
        $workflowService->seedPredefinedStates();
    }

    public function test_can_list_global_workflow_states(): void
    {
        $states = WorkflowState::global()->get();

        $this->assertCount(9, $states);
        $this->assertTrue($states->contains('slug', 'open'));
        $this->assertTrue($states->contains('slug', 'in_progress'));
        $this->assertTrue($states->contains('slug', 'closed'));
    }

    public function test_can_create_custom_workflow_state(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $project = Project::factory()->create();

        $response = $this->actingAs($admin)
            ->post(route('admin.workflows.store'), [
                'name' => 'Testing',
                'label' => 'In Testing',
                'description' => 'Ticket is being tested',
                'color' => 'purple',
                'icon' => 'test',
                'order' => 10,
                'project_id' => $project->id,
                'is_closed' => false,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('workflow_states', [
            'name' => 'Testing',
            'slug' => 'testing',
            'project_id' => $project->id,
            'is_predefined' => false,
        ]);
    }

    public function test_cannot_create_workflow_state_without_permission(): void
    {
        $user = User::factory()->create();
        $user->assignRole('agent');

        $response = $this->actingAs($user)
            ->post(route('admin.workflows.store'), [
                'name' => 'Custom State',
                'label' => 'Custom',
                'color' => 'blue',
            ]);

        $response->assertForbidden();
    }

    public function test_can_update_custom_workflow_state(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $state = WorkflowState::create([
            'name' => 'Custom',
            'slug' => 'custom',
            'label' => 'Custom State',
            'color' => 'blue',
            'is_predefined' => false,
            'order' => 10,
        ]);

        $response = $this->actingAs($admin)
            ->patch(route('admin.workflows.update', $state), [
                'name' => 'Updated',
                'label' => 'Updated State',
                'color' => 'green',
                'order' => 20,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('workflow_states', [
            'id' => $state->id,
            'name' => 'Updated',
            'label' => 'Updated State',
            'color' => 'green',
        ]);
    }

    public function test_cannot_update_predefined_workflow_state(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $state = WorkflowState::where('is_predefined', true)->first();

        $response = $this->actingAs($admin)
            ->patch(route('admin.workflows.update', $state), [
                'name' => 'Modified',
                'label' => 'Modified',
                'color' => 'red',
            ]);

        $response->assertForbidden();
    }

    public function test_can_delete_custom_workflow_state(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $state = WorkflowState::create([
            'name' => 'Custom',
            'slug' => 'custom',
            'label' => 'Custom State',
            'color' => 'blue',
            'is_predefined' => false,
            'order' => 10,
        ]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.workflows.destroy', $state));

        $response->assertRedirect();
        $this->assertDatabaseMissing('workflow_states', ['id' => $state->id]);
    }

    public function test_project_returns_correct_workflow_states(): void
    {
        $project = Project::factory()->create(['workflow_type' => 'global']);

        $states = $project->getAvailableStates();

        $this->assertGreaterThanOrEqual(9, $states->count());
        $this->assertTrue($states->every(fn ($state) => $state->project_id === null));
    }

    public function test_project_with_custom_workflow_returns_custom_states(): void
    {
        $project = Project::factory()->create(['workflow_type' => 'custom']);

        WorkflowState::create([
            'name' => 'Custom',
            'slug' => 'custom',
            'label' => 'Custom',
            'color' => 'blue',
            'project_id' => $project->id,
            'is_predefined' => false,
            'order' => 1,
        ]);

        $states = $project->getAvailableStates();

        $this->assertCount(1, $states);
        $this->assertEquals($project->id, $states->first()->project_id);
    }
}

