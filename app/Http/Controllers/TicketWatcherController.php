<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketWatcherController extends Controller
{
    /**
     * Add a watcher to a ticket
     */
    public function store(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $ticket->watchers()->syncWithoutDetaching([$data['user_id']]);

        // Notify the added watcher (and others) that a watcher was added
        $addedUser = User::find($data['user_id']);
        if ($addedUser) {
            $recipients = collect([$addedUser])
                ->merge([$ticket->user, $ticket->assignedUser])
                ->filter()
                ->unique('id')
                ->reject(fn($u) => $u->id === (Auth::id() ?? 0));
            foreach ($recipients as $recipient) {
                $recipient->notify(new \App\Notifications\TicketUpdated(
                    $ticket,
                    'Watcher added by ' . (Auth::user()->name ?? 'System'),
                    [['field' => 'watchers', 'old' => '—', 'new' => $addedUser->name]],
                    Auth::id() ?? 0,
                    Auth::user()->name ?? 'System'
                ));
            }
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'Watcher added.');
    }

    /**
     * Remove a watcher from a ticket
     */
    public function destroy(Ticket $ticket, User $user)
    {
        $ticket->watchers()->detach($user->id);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Watcher removed.');
    }
}
