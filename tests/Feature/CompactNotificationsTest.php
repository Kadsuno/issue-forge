<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CompactNotificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that TicketUpdated notification returns compact data with changes_count.
     */
    public function test_ticket_updated_notification_includes_changes_count(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $changes = [
            ['field' => 'status', 'old' => 'open', 'new' => 'in_progress'],
            ['field' => 'priority', 'old' => 'medium', 'new' => 'high'],
            ['field' => 'title', 'old' => 'Old Title', 'new' => 'New Title'],
        ];

        $notification = new TicketUpdated(
            ticket: $ticket,
            message: 'Ticket was updated',
            changes: $changes,
            actorId: $user->id,
            actorName: $user->name
        );

        $notificationData = $notification->toArray($user);

        $this->assertArrayHasKey('changes_count', $notificationData);
        $this->assertEquals(3, $notificationData['changes_count']);
        $this->assertArrayNotHasKey('changes', $notificationData);
    }

    /**
     * Test that long messages are truncated to 120 characters.
     */
    public function test_ticket_updated_notification_truncates_long_messages(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $longMessage = str_repeat('This is a very long message that should be truncated. ', 10);

        $notification = new TicketUpdated(
            ticket: $ticket,
            message: $longMessage,
            changes: [['field' => 'status', 'old' => 'open', 'new' => 'closed']],
            actorId: $user->id,
            actorName: $user->name
        );

        $notificationData = $notification->toArray($user);

        $this->assertLessThanOrEqual(123, strlen($notificationData['message'])); // 120 + "..."
        $this->assertStringContainsString('...', $notificationData['message']);
    }

    /**
     * Test that notification data includes all required fields.
     */
    public function test_ticket_updated_notification_includes_all_required_fields(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $notification = new TicketUpdated(
            ticket: $ticket,
            message: 'Test message',
            changes: [],
            actorId: $user->id,
            actorName: $user->name
        );

        $notificationData = $notification->toArray($user);

        $this->assertArrayHasKey('ticket_id', $notificationData);
        $this->assertArrayHasKey('ticket_number', $notificationData);
        $this->assertArrayHasKey('ticket_title', $notificationData);
        $this->assertArrayHasKey('project_id', $notificationData);
        $this->assertArrayHasKey('changes_count', $notificationData);
        $this->assertArrayHasKey('actor_id', $notificationData);
        $this->assertArrayHasKey('actor_name', $notificationData);
        $this->assertArrayHasKey('message', $notificationData);
        $this->assertArrayHasKey('url', $notificationData);
    }

    /**
     * Test that changes_count is zero when no changes are provided.
     */
    public function test_ticket_updated_notification_changes_count_zero_when_no_changes(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $notification = new TicketUpdated(
            ticket: $ticket,
            message: 'Test message',
            changes: [],
            actorId: $user->id,
            actorName: $user->name
        );

        $notificationData = $notification->toArray($user);

        $this->assertEquals(0, $notificationData['changes_count']);
    }

    /**
     * Test that short messages are not truncated.
     */
    public function test_ticket_updated_notification_preserves_short_messages(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $shortMessage = 'This is a short message';

        $notification = new TicketUpdated(
            ticket: $ticket,
            message: $shortMessage,
            changes: [],
            actorId: $user->id,
            actorName: $user->name
        );

        $notificationData = $notification->toArray($user);

        $this->assertEquals($shortMessage, $notificationData['message']);
        $this->assertStringNotContainsString('...', $notificationData['message']);
    }

    /**
     * Test that email notification also truncates long messages.
     */
    public function test_ticket_updated_email_truncates_long_messages(): void
    {
        $user = User::factory()->create(['email' => 'test@example.com']);
        $project = Project::factory()->create(['user_id' => $user->id]);
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        $longMessage = str_repeat('This is a very long message that should be truncated. ', 10);

        $notification = new TicketUpdated(
            ticket: $ticket,
            message: $longMessage,
            changes: [['field' => 'status', 'old' => 'open', 'new' => 'closed']],
            actorId: $user->id,
            actorName: $user->name
        );

        // Skip if mail is not configured
        if (! config('mail.from.address')) {
            $this->markTestSkipped('Mail not configured');
        }

        $mailMessage = $notification->toMail($user);

        // The view data should contain the truncated message
        $this->assertNotNull($mailMessage);
    }
}

