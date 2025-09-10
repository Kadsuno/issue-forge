<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProjectAndTicketDescriptionPlacementTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_description_is_not_in_header_but_below_in_card(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create([
            'name' => 'Test Project',
            'description' => '# Header in description',
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        $response = $this->get(route('projects.show', $project->id));
        $response->assertOk();

        // Page should render description within a card container after header
        $response->assertSee('Header in description', escape: false);
        $response->assertSee('<div class="card p-6 sm:p-8 w-full">', escape: false);
    }

    public function test_ticket_description_is_below_header_in_card(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create([
            'name' => 'Test Project',
            'user_id' => $user->id,
            'is_active' => true,
        ]);
        $ticket = Ticket::query()->create([
            'title' => 'My Ticket',
            'number' => 'TP-1',
            'project_id' => $project->id,
            'user_id' => $user->id,
            'description' => 'Ticket description paragraph',
            'status' => 'open',
            'priority' => 'medium',
        ]);

        $response = $this->get(route('tickets.show', $ticket));
        $response->assertOk();

        // Should render the description card after header
        $response->assertSee('Ticket description paragraph', escape: false);
        $response->assertSee('<div class="card p-6 sm:p-8 w-full">', escape: false);
    }
}
