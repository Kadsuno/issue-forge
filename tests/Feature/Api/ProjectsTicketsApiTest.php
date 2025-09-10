<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProjectsTicketsApiTest extends TestCase
{
    use RefreshDatabase;

    private function authHeader(): array
    {
        $token = config('api.admin_token') ?: 'test-token';

        config()->set('api.admin_token', $token);

        return ['Authorization' => 'Bearer '.$token];
    }

    public function test_projects_crud_flow(): void
    {
        // index unauthorized
        $this->get('/api/v1/projects')->assertStatus(401);

        // create
        $res = $this->postJson('/api/v1/projects', [
            'name' => 'Acme',
            'description' => 'Desc',
        ], $this->authHeader())->assertCreated();

        $id = $res->json('data.id');

        // show
        $this->getJson('/api/v1/projects/'.$id, $this->authHeader())
            ->assertOk()
            ->assertJsonPath('data.name', 'Acme');

        // update
        $this->patchJson('/api/v1/projects/'.$id, [
            'description' => 'Updated',
        ], $this->authHeader())->assertOk()->assertJsonPath('data.description', 'Updated');

        // delete
        $this->deleteJson('/api/v1/projects/'.$id, [], $this->authHeader())->assertNoContent();
    }

    public function test_tickets_crud_flow(): void
    {
        $project = Project::factory()->create(['name' => 'P1']);

        // create
        $res = $this->postJson('/api/v1/tickets', [
            'project_id' => $project->id,
            'title' => 'T1',
            'description' => 'D1',
            'status' => 'open',
            'priority' => 'medium',
        ], $this->authHeader())->assertCreated();

        $id = $res->json('data.id');

        // index with filter
        $this->getJson('/api/v1/tickets?project_id='.$project->id, $this->authHeader())
            ->assertOk()
            ->assertJsonStructure(['data']);

        // show
        $this->getJson('/api/v1/tickets/'.$id, $this->authHeader())
            ->assertOk()
            ->assertJsonPath('data.title', 'T1');

        // update
        $this->patchJson('/api/v1/tickets/'.$id, [
            'status' => 'resolved',
        ], $this->authHeader())->assertOk()->assertJsonPath('data.status', 'resolved');

        // delete
        $this->deleteJson('/api/v1/tickets/'.$id, [], $this->authHeader())->assertNoContent();
    }
}


