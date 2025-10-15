<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_unique_slug_from_title(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->make([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Test Ticket',
        ]);
        $ticket->save();

        $this->assertNotEmpty($ticket->slug);
        $this->assertEquals('test-ticket', $ticket->slug);
    }

    public function test_generates_unique_slug_with_suffix_for_duplicates(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        
        $ticket1 = Ticket::factory()->make([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Duplicate'
        ]);
        $ticket1->save();
        
        $ticket2 = Ticket::factory()->make([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Duplicate'
        ]);
        $ticket2->save();
        
        $ticket3 = Ticket::factory()->make([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => 'Duplicate'
        ]);
        $ticket3->save();

        $this->assertEquals('duplicate', $ticket1->slug);
        $this->assertStringStartsWith('duplicate-', $ticket2->slug);
        $this->assertStringStartsWith('duplicate-', $ticket3->slug);
        $this->assertNotEquals($ticket2->slug, $ticket3->slug);
    }

    public function test_uses_fallback_slug_when_title_is_empty(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = new Ticket([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'title' => '',
            'status' => 'open',
            'priority' => 'medium',
        ]);
        $ticket->save();

        $this->assertStringStartsWith('ticket-', $ticket->slug);
    }

    public function test_uses_route_key_as_slug(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $this->assertEquals('slug', $ticket->getRouteKeyName());
    }

    public function test_belongs_to_project(): void
    {
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create(['project_id' => $project->id]);

        $this->assertInstanceOf(Project::class, $ticket->project);
        $this->assertEquals($project->id, $ticket->project->id);
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $ticket->user);
        $this->assertEquals($user->id, $ticket->user->id);
    }

    public function test_belongs_to_assigned_user(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $ticket->assignedUser);
        $this->assertEquals($user->id, $ticket->assignedUser->id);
    }

    public function test_has_many_comments(): void
    {
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create(['project_id' => $project->id]);
        $comment = TicketComment::factory()->create(['ticket_id' => $ticket->id]);

        $this->assertInstanceOf(TicketComment::class, $ticket->comments->first());
        $this->assertCount(1, $ticket->comments);
    }

    public function test_has_many_time_entries(): void
    {
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create(['project_id' => $project->id]);
        $timeEntry = TimeEntry::factory()->create(['ticket_id' => $ticket->id]);

        $this->assertInstanceOf(TimeEntry::class, $ticket->timeEntries->first());
        $this->assertCount(1, $ticket->timeEntries);
    }

    public function test_many_to_many_watchers(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create(['project_id' => $project->id]);
        $ticket->watchers()->attach($user->id);

        $this->assertInstanceOf(User::class, $ticket->watchers->first());
        $this->assertCount(1, $ticket->watchers);
    }

    public function test_casts_due_date_to_datetime(): void
    {
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'due_date' => '2025-12-31 23:59:59',
        ]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $ticket->due_date);
    }

    public function test_casts_estimate_minutes_to_integer(): void
    {
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'estimate_minutes' => '120',
        ]);

        $this->assertIsInt($ticket->estimate_minutes);
        $this->assertEquals(120, $ticket->estimate_minutes);
    }

    public function test_fillable_attributes(): void
    {
        $data = [
            'title' => 'Fillable Test',
            'description' => 'Test description',
            'status' => 'open',
            'priority' => 'high',
            'project_id' => 1,
            'parent_ticket_id' => null,
            'user_id' => 1,
            'assigned_to' => 1,
            'due_date' => '2025-12-31',
            'type' => 'bug',
            'severity' => 'critical',
            'estimate_minutes' => 120,
            'labels' => 'test,labels',
        ];

        $ticket = new Ticket($data);

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $ticket->getAttributes());
        }
    }

    public function test_can_filter_tickets_by_status(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $openTicket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'status' => 'open'
        ]);
        $closedTicket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'status' => 'closed'
        ]);

        $openTickets = Ticket::where('status', 'open')->get();

        $this->assertCount(1, $openTickets);
        $this->assertEquals('open', $openTickets->first()->status);
    }

    public function test_can_filter_tickets_by_assigned_user(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $assignedTicket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'assigned_to' => $user->id
        ]);
        $unassignedTicket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'assigned_to' => null
        ]);

        $assignedTickets = Ticket::where('assigned_to', $user->id)->get();

        $this->assertCount(1, $assignedTickets);
        $this->assertEquals($user->id, $assignedTickets->first()->assigned_to);
    }
}

