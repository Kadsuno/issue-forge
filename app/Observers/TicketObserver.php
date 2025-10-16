<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Illuminate\Support\Facades\Auth;

final class TicketObserver
{
    /**
     * Handle the Ticket "updating" event.
     */
    public function updating(Ticket $ticket): void
    {
        // Check if status is being changed
        if ($ticket->isDirty('status')) {
            $original = $ticket->getOriginal('status');
            $new = $ticket->status;

            // Only log if we have a user context
            $userId = Auth::id();
            if ($userId && $original !== $new) {
                // We'll create the history record after the update
                $ticket->_pendingStatusHistory = [
                    'from_status' => $original,
                    'to_status' => $new,
                    'user_id' => $userId,
                ];
            }
        }
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        // Check if we have a pending status history to create
        if (isset($ticket->_pendingStatusHistory)) {
            TicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'from_status' => $ticket->_pendingStatusHistory['from_status'],
                'to_status' => $ticket->_pendingStatusHistory['to_status'],
                'user_id' => $ticket->_pendingStatusHistory['user_id'],
            ]);

            unset($ticket->_pendingStatusHistory);
        }
    }
}

