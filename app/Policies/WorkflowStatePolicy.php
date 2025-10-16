<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\WorkflowState;

final class WorkflowStatePolicy
{
    /**
     * Determine if the user can create workflow states
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('workflow.create_custom_states');
    }

    /**
     * Determine if the user can update the workflow state
     */
    public function update(User $user, WorkflowState $state): bool
    {
        // Cannot edit predefined states
        if ($state->is_predefined) {
            return false;
        }

        return $user->hasPermissionTo('workflow.manage');
    }

    /**
     * Determine if the user can delete the workflow state
     */
    public function delete(User $user, WorkflowState $state): bool
    {
        // Cannot delete predefined states
        if ($state->is_predefined) {
            return false;
        }

        return $user->hasPermissionTo('workflow.manage');
    }

    /**
     * Determine if the user can assign this state to a ticket
     */
    public function assignToTicket(User $user, WorkflowState $state): bool
    {
        return $state->canBeSetBy($user);
    }
}
