<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

/**
 * Authorization policy for Project model.
 *
 * Defines who can perform what actions on projects.
 * Admins have full access, regular users have limited access.
 */
final class ProjectPolicy
{
    /**
     * Determine if the user can view any projects.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view projects list
        return true;
    }

    /**
     * Determine if the user can view the project.
     */
    public function view(User $user, Project $project): bool
    {
        // All authenticated users can view individual projects
        // In the future, this could check project visibility settings
        return true;
    }

    /**
     * Determine if the user can create projects.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create projects
        return true;
    }

    /**
     * Determine if the user can update the project.
     */
    public function update(User $user, Project $project): bool
    {
        // Admins can update any project
        if ($user->isAdmin()) {
            return true;
        }

        // Project owner can update their project
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the user can delete the project.
     */
    public function delete(User $user, Project $project): bool
    {
        // Admins can delete any project
        if ($user->isAdmin()) {
            return true;
        }

        // Project owner can delete their project
        return $user->id === $project->user_id;
    }

    /**
     * Determine if the user can archive/unarchive the project.
     */
    public function archive(User $user, Project $project): bool
    {
        // Same rules as update
        return $this->update($user, $project);
    }
}
