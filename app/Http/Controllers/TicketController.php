<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\TicketUpdated;
use App\Services\WorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class TicketController extends Controller
{
    public function __construct(private readonly WorkflowService $workflowService)
    {
    }
    /**
     * List tickets assigned to the current user.
     */
    public function myTickets(): View
    {
        $tickets = Ticket::with(['project', 'user', 'assignedUser'])
            ->where('assigned_to', Auth::id())
            ->latest()
            ->get();

        return view('tickets.mine', compact('tickets'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Project $project): View
    {
        $tickets = $project->tickets()->with(['user', 'assignedUser'])->latest()->get();

        return view('tickets.index', compact('project', 'tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project): View
    {
        $users = User::orderBy('name')->get();
        $parentOptions = $project->tickets()->orderByDesc('id')->get(['id', 'title']);
        $workflowStates = $this->workflowService->getStatesForProject($project);
        $statuses = $workflowStates->pluck('label', 'slug')->toArray();
        $priorities = Ticket::getPriorities();

        return view('tickets.create', compact('project', 'users', 'statuses', 'priorities', 'parentOptions', 'workflowStates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        $validStatuses = $this->workflowService->getStatesForProject($project)->pluck('slug')->toArray();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:'.implode(',', $validStatuses),
            'priority' => 'required|in:'.implode(',', array_keys(Ticket::getPriorities())),
            'type' => 'nullable|in:'.implode(',', array_keys(Ticket::getTypes())),
            'severity' => 'nullable|in:'.implode(',', array_keys(Ticket::getSeverities())),
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'estimate_minutes' => 'nullable|integer|min:0|max:100000',
            'labels' => 'nullable|string|max:1000',
            'parent_ticket_id' => 'nullable|exists:tickets,id',
        ]);

        $ticket = $project->tickets()->create([
            ...$validated,
            'user_id' => Auth::id(),
            'assigned_to' => $validated['assigned_to'] ?? $project->default_assignee_id,
            'priority' => $validated['priority'] ?? ($project->priority ?? 'medium'),
            'status' => $validated['status'] ?? 'open',
            'type' => $validated['type'] ?? 'task',
            'parent_ticket_id' => $validated['parent_ticket_id'] ?? null,
        ]);

        // Ensure creator and assignee are watchers by default
        $watcherIds = array_values(array_unique(array_filter([
            $ticket->user_id,
            $ticket->assigned_to,
        ])));
        if (! empty($watcherIds)) {
            $ticket->watchers()->syncWithoutDetaching($watcherIds);
        }

        // Notify: creator, assignee, and watchers (except actor) that ticket was created
        $recipients = $ticket->watchers()->get()->merge([
            $ticket->user,
            $ticket->assignedUser,
        ])->filter()->unique('id')->reject(fn ($u) => $u->id === Auth::id());
        foreach ($recipients as $recipient) {
            $recipient->notify(new \App\Notifications\TicketUpdated(
                $ticket,
                'Ticket created by '.Auth::user()->name,
                [],
                Auth::id(),
                Auth::user()->name
            ));
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket): View
    {
        $ticket->load(['project', 'user', 'assignedUser']);

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket): View
    {
        $ticket->load(['project', 'assignedUser']);
        $users = User::orderBy('name')->get();
        $parentOptions = $ticket->project->tickets()->where('id', '!=', $ticket->id)->orderByDesc('id')->get(['id', 'title']);
        $workflowStates = $this->workflowService->getStatesForProject($ticket->project);
        $statuses = $workflowStates->pluck('label', 'slug')->toArray();
        $priorities = Ticket::getPriorities();

        return view('tickets.edit', compact('ticket', 'users', 'statuses', 'priorities', 'parentOptions', 'workflowStates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket): RedirectResponse
    {
        $validStatuses = $this->workflowService->getStatesForProject($ticket->project)->pluck('slug')->toArray();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:'.implode(',', $validStatuses),
            'priority' => 'required|in:'.implode(',', array_keys(Ticket::getPriorities())),
            'type' => 'nullable|in:'.implode(',', array_keys(Ticket::getTypes())),
            'severity' => 'nullable|in:'.implode(',', array_keys(Ticket::getSeverities())),
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:today',
            'estimate_minutes' => 'nullable|integer|min:0|max:100000',
            'labels' => 'nullable|string|max:1000',
            'parent_ticket_id' => 'nullable|exists:tickets,id',
        ]);

        $original = $ticket->getOriginal();
        $ticket->update([
            ...$validated,
            'assigned_to' => $validated['assigned_to'] ?? null,
        ]);

        // Ensure creator and (current) assignee are watchers
        $watcherIds = array_values(array_unique(array_filter([
            $ticket->user_id,
            $ticket->assigned_to,
        ])));
        if (! empty($watcherIds)) {
            $ticket->watchers()->syncWithoutDetaching($watcherIds);
        }

        // Notify: owner, assignee, watchers (except actor)
        $changed = collect($ticket->getChanges())->except(['updated_at']);
        if ($changed->isNotEmpty()) {
            $changedSummary = $changed->map(function ($newValue, $key) use ($original) {
                $oldValue = $original[$key] ?? null;

                // Humanize certain fields for notifications
                if ($key === 'assigned_to') {
                    $oldName = $oldValue ? (\App\Models\User::find($oldValue)?->name ?? (string) $oldValue) : null;
                    $newName = $newValue ? (\App\Models\User::find($newValue)?->name ?? (string) $newValue) : null;

                    return [
                        'field' => 'assignee',
                        'old' => $oldName ?? 'Unassigned',
                        'new' => $newName ?? 'Unassigned',
                    ];
                }

                return [
                    'field' => $key,
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            })->values()->all();
            $recipients = $ticket->watchers()->get()->merge([
                $ticket->user,
                $ticket->assignedUser,
            ])->filter()->unique('id')->reject(fn ($u) => $u->id === Auth::id());
            foreach ($recipients as $recipient) {
                $recipient->notify(new TicketUpdated(
                    $ticket,
                    'Ticket updated by '.Auth::user()->name,
                    $changedSummary,
                    Auth::id(),
                    Auth::user()->name
                ));
            }
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        $project = $ticket->project;
        $ticket->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Ticket deleted successfully!');
    }
}
