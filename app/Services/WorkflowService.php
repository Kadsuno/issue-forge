<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use App\Models\User;
use App\Models\WorkflowState;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class WorkflowService
{
    /**
     * Get available workflow states for a project
     */
    public function getStatesForProject(Project $project): Collection
    {
        return $project->getAvailableStates();
    }

    /**
     * Get workflow states filtered by user permissions
     */
    public function getStatesForUser(User $user, Project $project): Collection
    {
        $states = $this->getStatesForProject($project);

        return $states->filter(fn (WorkflowState $state) => $state->canBeSetBy($user));
    }

    /**
     * Check if a ticket can transition to a new status
     */
    public function canTransition(Ticket $ticket, string $toStatus, User $user): bool
    {
        $targetState = WorkflowState::where('slug', $toStatus)
            ->where(function ($query) use ($ticket) {
                $query->whereNull('project_id')
                    ->orWhere('project_id', $ticket->project_id);
            })
            ->first();

        if (! $targetState) {
            return false;
        }

        return $targetState->canBeSetBy($user);
    }

    /**
     * Transition a ticket to a new status
     */
    public function transition(Ticket $ticket, string $toStatus, User $user, ?string $comment = null): void
    {
        if (! $this->canTransition($ticket, $toStatus, $user)) {
            throw new \RuntimeException('User does not have permission to transition to this status.');
        }

        $fromStatus = $ticket->status;

        DB::transaction(function () use ($ticket, $fromStatus, $toStatus, $user, $comment): void {
            // Update ticket status
            $ticket->update([
                'previous_status' => $fromStatus,
                'status' => $toStatus,
            ]);

            // Log status change in history
            TicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'user_id' => $user->id,
                'comment' => $comment,
            ]);
        });
    }

    /**
     * Seed predefined workflow states
     */
    public function seedPredefinedStates(): void
    {
        $states = [
            [
                'name' => 'Open',
                'slug' => 'open',
                'label' => 'Open',
                'description' => 'Ticket is open and awaiting assignment or action',
                'color' => 'gray',
                'icon' => 'circle',
                'is_predefined' => true,
                'is_closed' => false,
                'order' => 1,
            ],
            [
                'name' => 'In Progress',
                'slug' => 'in_progress',
                'label' => 'In Progress',
                'description' => 'Ticket is actively being worked on',
                'color' => 'blue',
                'icon' => 'arrow-right',
                'is_predefined' => true,
                'is_closed' => false,
                'order' => 2,
            ],
            [
                'name' => 'Testing',
                'slug' => 'testing',
                'label' => 'Testing',
                'description' => 'Ticket is in testing phase',
                'color' => 'purple',
                'icon' => 'beaker',
                'is_predefined' => true,
                'is_closed' => false,
                'order' => 3,
            ],
            [
                'name' => 'Review',
                'slug' => 'review',
                'label' => 'Review',
                'description' => 'Ticket is under review',
                'color' => 'purple',
                'icon' => 'eye',
                'is_predefined' => true,
                'is_closed' => false,
                'order' => 4,
            ],
            [
                'name' => 'Waiting',
                'slug' => 'waiting',
                'label' => 'Waiting',
                'description' => 'Waiting for external input or dependency',
                'color' => 'yellow',
                'icon' => 'clock',
                'is_predefined' => true,
                'is_closed' => false,
                'order' => 5,
            ],
            [
                'name' => 'Blocked',
                'slug' => 'blocked',
                'label' => 'Blocked',
                'description' => 'Ticket is blocked by an issue or dependency',
                'color' => 'red',
                'icon' => 'x-circle',
                'is_predefined' => true,
                'is_closed' => false,
                'order' => 6,
            ],
            [
                'name' => 'Resolved',
                'slug' => 'resolved',
                'label' => 'Resolved',
                'description' => 'Ticket has been resolved',
                'color' => 'green',
                'icon' => 'check',
                'is_predefined' => true,
                'is_closed' => false,
                'order' => 7,
            ],
            [
                'name' => 'Won\'t Fix',
                'slug' => 'wontfix',
                'label' => 'Won\'t Fix',
                'description' => 'Ticket will not be fixed or implemented',
                'color' => 'gray',
                'icon' => 'ban',
                'is_predefined' => true,
                'is_closed' => true,
                'order' => 8,
            ],
            [
                'name' => 'Closed',
                'slug' => 'closed',
                'label' => 'Closed',
                'description' => 'Ticket is closed and completed',
                'color' => 'gray',
                'icon' => 'check-circle',
                'is_predefined' => true,
                'is_closed' => true,
                'order' => 9,
            ],
        ];

        foreach ($states as $state) {
            WorkflowState::updateOrCreate(
                ['slug' => $state['slug'], 'project_id' => null],
                $state
            );
        }
    }
}

