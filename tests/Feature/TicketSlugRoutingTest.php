<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TicketSlugRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_uses_slug_for_routes_and_is_generated_from_title(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create(['user_id' => $user->id]);

        $ticket = $project->tickets()->create([
            'title' => 'Fix broken search input',
            'description' => 'Details',
            'status' => 'open',
            'priority' => 'medium',
            'user_id' => $user->id,
        ]);

        $this->assertNotEmpty($ticket->slug);
        $this->assertStringContainsString('fix-broken-search-input', $ticket->slug);

        $this->get(route('tickets.show', $ticket))->assertOk();
    }
}
