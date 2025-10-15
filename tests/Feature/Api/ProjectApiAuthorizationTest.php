<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProjectApiAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private string $adminToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminToken = config('api.admin_token');
    }

    public function test_requires_authentication_token(): void
    {
        $response = $this->getJson('/api/v1/projects');

        $response->assertStatus(401);
    }

    public function test_rejects_invalid_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
            'Accept' => 'application/json',
        ])->getJson('/api/v1/projects');

        $response->assertStatus(403);
    }

    public function test_accepts_valid_admin_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
            'Accept' => 'application/json',
        ])->getJson('/api/v1/projects');

        $response->assertStatus(200);
    }

    public function test_create_project_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/projects', [
            'name' => 'Test Project',
        ]);

        $response->assertStatus(401);
    }

    public function test_update_project_requires_authentication(): void
    {
        $project = Project::factory()->create();

        $response = $this->patchJson("/api/v1/projects/{$project->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(401);
    }

    public function test_delete_project_requires_authentication(): void
    {
        $project = Project::factory()->create();

        $response = $this->deleteJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(401);
    }

    public function test_regular_user_can_create_project_via_admin_token(): void
    {
        // API uses admin token auth, not per-user tokens
        // This test verifies that the API works with proper token

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/projects', [
            'name' => 'User Project',
            'description' => 'Created via admin token',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'status',
                ],
            ]);
    }

    public function test_admin_can_create_project_via_token(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/projects', [
            'name' => 'Admin Project',
            'description' => 'Created via admin token',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                ],
            ]);
    }

    public function test_admin_can_update_project_via_token(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
            'Accept' => 'application/json',
        ])->patchJson("/api/v1/projects/{$project->id}", [
            'name' => 'Updated by Admin',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => 'Updated by Admin',
        ]);
    }

    public function test_admin_can_delete_project_created_by_user(): void
    {
        $owner = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $owner->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_admin_can_delete_any_project_via_token(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->adminToken,
            'Accept' => 'application/json',
        ])->deleteJson("/api/v1/projects/{$project->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
