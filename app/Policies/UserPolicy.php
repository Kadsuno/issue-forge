<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy to authorize actions on User management within the admin area.
 */
class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $actingUser): bool
    {
        return $actingUser->can('user.view');
    }

    /**
     * Determine whether the user can view a specific user.
     */
    public function view(User $actingUser, User $targetUser): bool
    {
        return $actingUser->can('user.view');
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $actingUser): bool
    {
        return $actingUser->can('user.create');
    }

    /**
     * Determine whether the user can update a user.
     */
    public function update(User $actingUser, User $targetUser): bool
    {
        return $actingUser->can('user.update');
    }

    /**
     * Determine whether the user can delete a user.
     */
    public function delete(User $actingUser, User $targetUser): bool
    {
        return $actingUser->can('user.delete');
    }
}
