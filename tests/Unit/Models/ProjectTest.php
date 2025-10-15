<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_unique_slug_from_name(): void
    {
        $project = Project::factory()->make(['name' => 'Test Project']);
        $project->save();

        $this->assertNotEmpty($project->slug);
        $this->assertEquals('test-project', $project->slug);
    }

    public function test_generates_unique_slug_with_suffix_for_duplicates(): void
    {
        $project1 = Project::factory()->make(['name' => 'Duplicate']);
        $project1->save();
        $project2 = Project::factory()->make(['name' => 'Duplicate']);
        $project2->save();
        $project3 = Project::factory()->make(['name' => 'Duplicate']);
        $project3->save();

        $this->assertEquals('duplicate', $project1->slug);
        $this->assertStringStartsWith('duplicate-', $project2->slug);
        $this->assertStringStartsWith('duplicate-', $project3->slug);
        $this->assertNotEquals($project2->slug, $project3->slug);
    }

    public function test_uses_fallback_slug_when_name_is_empty(): void
    {
        $project = new Project(['name' => '', 'user_id' => User::factory()->create()->id]);
        $project->save();

        // Should be 'project' or 'project-N' if collision
        $this->assertTrue(
            $project->slug === 'project' || str_starts_with($project->slug, 'project-'),
            "Expected slug to be 'project' or start with 'project-', got: {$project->slug}"
        );
    }

    public function test_uses_route_key_as_slug(): void
    {
        $project = Project::factory()->create();

        $this->assertEquals('slug', $project->getRouteKeyName());
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $project->user);
        $this->assertEquals($user->id, $project->user->id);
    }

    public function test_has_many_tickets(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(Ticket::class, $project->tickets->first());
        $this->assertCount(1, $project->tickets);
    }

    public function test_casts_is_active_to_boolean(): void
    {
        $project = Project::factory()->create(['is_active' => 1]);

        $this->assertIsBool($project->is_active);
        $this->assertTrue($project->is_active);
    }

    public function test_casts_dates(): void
    {
        $project = Project::factory()->create([
            'start_date' => '2025-01-01',
            'due_date' => '2025-12-31',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $project->start_date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $project->due_date);
    }

    public function test_fillable_attributes(): void
    {
        $data = [
            'name' => 'Fillable Test',
            'description' => 'Test description',
            'slug' => 'fillable-test',
            'is_active' => true,
            'user_id' => 1,
            'start_date' => '2025-01-01',
            'due_date' => '2025-12-31',
            'default_assignee_id' => 1,
            'visibility' => 'public',
            'ticket_prefix' => 'FT',
            'color' => '#ff0000',
            'priority' => 'high',
        ];

        $project = new Project($data);

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $project->getAttributes());
        }
    }

    public function test_can_check_if_project_is_active(): void
    {
        $activeProject = Project::factory()->create(['is_active' => true]);
        $inactiveProject = Project::factory()->create(['is_active' => false]);

        $this->assertTrue($activeProject->is_active);
        $this->assertFalse($inactiveProject->is_active);
    }
}
