<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Illuminate\Support\Facades\Auth;

final class TicketObserver
{
    private static array $pendingHistories = [];

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
                // Store in static property to avoid model attribute pollution
                self::$pendingHistories[$ticket->id] = [
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
        if (isset(self::$pendingHistories[$ticket->id])) {
            $history = self::$pendingHistories[$ticket->id];

            TicketStatusHistory::create([
                'ticket_id' => $ticket->id,
                'from_status' => $history['from_status'],
                'to_status' => $history['to_status'],
                'user_id' => $history['user_id'],
            ]);

            unset(self::$pendingHistories[$ticket->id]);
        }
    }
}
