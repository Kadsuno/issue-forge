<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;

/**
 * Authorization policy for Ticket model.
 *
 * Defines who can perform what actions on tickets.
 * Admins have full access, regular users have limited access based on ownership and assignment.
 */
final class TicketPolicy
{
    /**
     * Determine if the user can view any tickets.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view tickets list
        return true;
    }

    /**
     * Determine if the user can view the ticket.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // All authenticated users can view individual tickets
        // In the future, this could check ticket/project visibility settings
        return true;
    }

    /**
     * Determine if the user can create tickets.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create tickets
        return true;
    }

    /**
     * Determine if the user can update the ticket.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Admins can update any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Ticket creator can update their ticket
        if ($user->id === $ticket->user_id) {
            return true;
        }

        // Assigned user can update their assigned ticket
        if ($user->id === $ticket->assigned_to) {
            return true;
        }

        // Project owner can update tickets in their project
        if ($ticket->project && $user->id === $ticket->project->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the ticket.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Admins can delete any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Ticket creator can delete their ticket
        if ($user->id === $ticket->user_id) {
            return true;
        }

        // Project owner can delete tickets in their project
        if ($ticket->project && $user->id === $ticket->project->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can assign the ticket to someone.
     */
    public function assign(User $user, Ticket $ticket): bool
    {
        // Admins can assign any ticket
        if ($user->isAdmin()) {
            return true;
        }

        // Project owner can assign tickets in their project
        if ($ticket->project && $user->id === $ticket->project->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can change the ticket status.
     */
    public function changeStatus(User $user, Ticket $ticket): bool
    {
        // Same rules as update
        return $this->update($user, $ticket);
    }

    /**
     * Determine if the user can add comments to the ticket.
     */
    public function comment(User $user, Ticket $ticket): bool
    {
        // All authenticated users can comment on tickets they can view
        return $this->view($user, $ticket);
    }
}
