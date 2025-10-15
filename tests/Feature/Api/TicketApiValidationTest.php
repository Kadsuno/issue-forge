<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TicketApiValidationTest extends TestCase
{
    use RefreshDatabase;

    private string $adminToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminToken = config('api.admin_token');
    }

    private function authenticatedHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->adminToken,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function test_create_ticket_requires_project_id(): void
    {
        $response = $this->withHeaders($this->authenticatedHeaders())
            ->postJson('/api/v1/tickets', [
                'title' => 'Test Ticket',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['project_id']);
    }

    public function test_create_ticket_requires_title(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->postJson('/api/v1/tickets', [
                'project_id' => $project->id,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_create_ticket_title_max_length(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->postJson('/api/v1/tickets', [
                'project_id' => $project->id,
                'title' => str_repeat('a', 201), // Exceeds 200 char limit
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_create_ticket_validates_project_exists(): void
    {
        $response = $this->withHeaders($this->authenticatedHeaders())
            ->postJson('/api/v1/tickets', [
                'project_id' => 99999, // Non-existent project
                'title' => 'Test Ticket',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['project_id']);
    }

    public function test_create_ticket_validates_status(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->postJson('/api/v1/tickets', [
                'project_id' => $project->id,
                'title' => 'Test Ticket',
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_create_ticket_validates_priority(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->postJson('/api/v1/tickets', [
                'project_id' => $project->id,
                'title' => 'Test Ticket',
                'priority' => 'invalid_priority',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['priority']);
    }

    public function test_create_ticket_accepts_valid_status(): void
    {
        $project = Project::factory()->create();

        foreach (['open', 'in_progress', 'resolved', 'closed'] as $status) {
            $response = $this->withHeaders($this->authenticatedHeaders())
                ->postJson('/api/v1/tickets', [
                    'project_id' => $project->id,
                    'title' => "Ticket with {$status} status",
                    'status' => $status,
                ]);

            $response->assertStatus(201);
        }
    }

    public function test_create_ticket_accepts_valid_priority(): void
    {
        $project = Project::factory()->create();

        foreach (['low', 'medium', 'high', 'urgent'] as $priority) {
            $response = $this->withHeaders($this->authenticatedHeaders())
                ->postJson('/api/v1/tickets', [
                    'project_id' => $project->id,
                    'title' => "Ticket with {$priority} priority",
                    'priority' => $priority,
                ]);

            $response->assertStatus(201);
        }
    }

    public function test_update_ticket_validates_title_length(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->patchJson("/api/v1/tickets/{$ticket->id}", [
                'title' => str_repeat('a', 201),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_update_ticket_validates_status(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->patchJson("/api/v1/tickets/{$ticket->id}", [
                'status' => 'invalid_status',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_update_ticket_allows_partial_updates(): void
    {
        $ticket = Ticket::factory()->create([
            'title' => 'Original Title',
            'priority' => 'low',
        ]);

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->patchJson("/api/v1/tickets/{$ticket->id}", [
                'priority' => 'urgent',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'Original Title', // Unchanged
            'priority' => 'urgent', // Changed
        ]);
    }

    public function test_create_ticket_with_all_fields(): void
    {
        $project = Project::factory()->create();

        $response = $this->withHeaders($this->authenticatedHeaders())
            ->postJson('/api/v1/tickets', [
                'project_id' => $project->id,
                'title' => 'Comprehensive Test Ticket',
                'description' => 'Detailed description of the issue',
                'status' => 'in_progress',
                'priority' => 'high',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'project_id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'project_id' => $project->id,
                    'title' => 'Comprehensive Test Ticket',
                    'status' => 'in_progress',
                    'priority' => 'high',
                ],
            ]);
    }
}

