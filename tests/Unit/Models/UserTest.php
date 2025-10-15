<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_generates_unique_slug_from_name(): void
    {
        $user = User::factory()->make(['name' => 'John Doe']);
        $user->save();

        $this->assertNotEmpty($user->slug);
        $this->assertEquals('john-doe', $user->slug);
    }

    public function test_generates_unique_slug_with_suffix_for_duplicates(): void
    {
        $user1 = User::factory()->make(['name' => 'Jane Smith']);
        $user1->save();
        $user2 = User::factory()->make(['name' => 'Jane Smith']);
        $user2->save();
        $user3 = User::factory()->make(['name' => 'Jane Smith']);
        $user3->save();

        $this->assertEquals('jane-smith', $user1->slug);
        $this->assertStringStartsWith('jane-smith-', $user2->slug);
        $this->assertStringStartsWith('jane-smith-', $user3->slug);
        $this->assertNotEquals($user2->slug, $user3->slug);
    }

    public function test_uses_fallback_slug_when_name_is_empty(): void
    {
        $user = new User([
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        $user->save();

        $this->assertStringStartsWith('user-', $user->slug);
    }

    public function test_uses_route_key_as_slug(): void
    {
        $user = User::factory()->create();

        $this->assertEquals('slug', $user->getRouteKeyName());
    }

    public function test_has_many_projects(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Project::class, $user->projects->first());
        $this->assertCount(1, $user->projects);
    }

    public function test_has_many_tickets(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'user_id' => $user->id,
            'project_id' => $project->id,
        ]);

        $this->assertInstanceOf(Ticket::class, $user->tickets->first());
        $this->assertCount(1, $user->tickets);
    }

    public function test_has_many_assigned_tickets(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create([
            'assigned_to' => $user->id,
            'project_id' => $project->id,
        ]);

        $this->assertInstanceOf(Ticket::class, $user->assignedTickets->first());
        $this->assertCount(1, $user->assignedTickets);
    }

    public function test_many_to_many_watched_tickets(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $ticket = Ticket::factory()->create(['project_id' => $project->id]);
        $user->watchedTickets()->attach($ticket->id);

        $this->assertInstanceOf(Ticket::class, $user->watchedTickets->first());
        $this->assertCount(1, $user->watchedTickets);
    }

    public function test_hidden_attributes(): void
    {
        $user = User::factory()->create();
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
    }

    public function test_casts_email_verified_at_to_datetime(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->email_verified_at);
    }

    public function test_casts_is_admin_to_boolean(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $this->assertIsBool($user->is_admin);
        $this->assertTrue($user->is_admin);
    }

    public function test_hashes_password(): void
    {
        $user = User::factory()->create(['password' => 'secret123']);

        $this->assertNotEquals('secret123', $user->password);
        $this->assertTrue(\Hash::check('secret123', $user->password));
    }

    public function test_fillable_attributes(): void
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'is_admin' => true,
            'slug' => 'test-user',
        ];

        $user = new User($data);

        foreach (array_keys($data) as $key) {
            $this->assertArrayHasKey($key, $user->getAttributes());
        }
    }
}
