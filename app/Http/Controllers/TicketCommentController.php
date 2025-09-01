<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Notifications\TicketCommentAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    /**
     * Show edit form for a comment.
     */
    public function edit(TicketComment $comment)
    {
        $ticket = $comment->ticket()->with('project')->first();
        return view('comments.edit', compact('comment', 'ticket'));
    }

    /**
     * Store a new comment for a ticket.
     */
    public function store(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $comment = $ticket->comments()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        // Notify owner, assignee, and watchers (exclude actor)
        $recipients = $ticket->watchers()->get()->merge([
            $ticket->user,
            $ticket->assignedUser,
        ])->filter()->unique('id')->reject(fn($u) => $u->id === Auth::id());
        foreach ($recipients as $recipient) {
            $recipient->notify(new TicketCommentAdded($ticket, $comment, Auth::user()->name));
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Update a ticket comment.
     */
    public function update(Request $request, TicketComment $comment)
    {
        $validated = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $comment->update($validated);

        return redirect()->route('tickets.show', $comment->ticket)->with('success', 'Comment updated.');
    }

    /**
     * Delete a ticket comment.
     */
    public function destroy(TicketComment $comment)
    {
        $ticket = $comment->ticket;
        $comment->delete();
        return redirect()->route('tickets.show', $ticket)->with('success', 'Comment deleted.');
    }
}
