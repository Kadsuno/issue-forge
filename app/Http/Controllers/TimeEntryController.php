<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            // Accept H:MM format only (e.g., 1:30 or 12:05)
            'minutes' => ['required', 'string', 'regex:/^\d{1,3}:\d{2}$/'],
            'spent_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        [$hours, $mins] = array_map('intval', explode(':', $data['minutes']));
        $totalMinutes = ($hours * 60) + $mins;

        $ticket->timeEntries()->create([
            'minutes' => $totalMinutes,
            'spent_at' => $data['spent_at'],
            'note' => $data['note'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Time booked.');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $ticket = $timeEntry->ticket;
        $timeEntry->delete();
        return redirect()->route('tickets.show', $ticket)->with('success', 'Time entry removed.');
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        $data = $request->validate([
            'minutes' => ['required', 'string', 'regex:/^\d{1,3}:\d{2}$/'],
            'spent_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        [$hours, $mins] = array_map('intval', explode(':', $data['minutes']));
        $totalMinutes = ($hours * 60) + $mins;

        $timeEntry->update([
            'minutes' => $totalMinutes,
            'spent_at' => $data['spent_at'],
            'note' => $data['note'] ?? null,
        ]);

        return redirect()->route('tickets.show', $timeEntry->ticket)->with('success', 'Time entry updated.');
    }
}
