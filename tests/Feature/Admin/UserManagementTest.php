<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\RolesAndPermissionsSeeder;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ensure non-admins cannot access user management.
     */
    public function test_non_admin_cannot_access_admin_users(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.users.index'))
            ->assertForbidden();
    }

    /**
     * Ensure admins can list users.
     */
    public function test_admin_can_list_users(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $admin = User::factory()->create(['is_admin' => true]);
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk();
    }

    /**
     * Ensure admin can create, update and delete a user.
     */
    public function test_admin_can_crud_users(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $admin = User::factory()->create(['is_admin' => true]);
        $admin->assignRole('admin');

        // Create
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $this->actingAs($admin)
            ->post(route('admin.users.store'), $payload)
            ->assertRedirect(route('admin.users.index'));

        $user = User::where('email', 'john@example.com')->firstOrFail();
        $this->assertFalse($user->is_admin);

        // Update (promote to admin)
        $updatePayload = [
            'name' => 'John D',
            'email' => 'john@example.com',
            'is_admin' => 1,
        ];

        $this->actingAs($admin)
            ->put(route('admin.users.update', $user), $updatePayload)
            ->assertRedirect(route('admin.users.index'));

        $user->refresh();
        $this->assertTrue($user->is_admin);
        $this->assertEquals('John D', $user->name);

        // Prevent self-delete
        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHas('error');

        // Delete the created user
        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $user))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['email' => 'john@example.com']);
    }
}
