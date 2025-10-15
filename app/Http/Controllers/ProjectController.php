<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'default_assignee_id' => 'nullable|exists:users,id',
            'visibility' => 'nullable|in:private,team,public',
            'ticket_prefix' => 'nullable|string|max:10',
            'color' => 'nullable|regex:/^#?[0-9a-fA-F]{6}$/',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
            'user_id' => Auth::id(),
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'default_assignee_id' => $validated['default_assignee_id'] ?? null,
            'visibility' => $validated['visibility'] ?? 'team',
            'ticket_prefix' => $validated['ticket_prefix'] ?? null,
            'color' => isset($validated['color']) ? (str_starts_with($validated['color'], '#') ? $validated['color'] : '#'.$validated['color']) : null,
            'priority' => $validated['priority'] ?? 'medium',
        ]);

        return redirect()->route('projects.show', $project->slug)
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        // Re-query to include aggregates and additional relations for the detail view
        $project = Project::query()
            ->whereKey($project->getKey())
            ->with([
                'user',
                'defaultAssignee',
                'tickets' => function ($query) {
                    $query->with(['user', 'assignedUser'])->latest();
                },
            ])
            ->withCount([
                'tickets',
                'tickets as open_tickets_count' => function ($q) {
                    $q->where('status', Ticket::STATUS_OPEN);
                },
                'tickets as in_progress_tickets_count' => function ($q) {
                    $q->where('status', Ticket::STATUS_IN_PROGRESS);
                },
                'tickets as closed_tickets_count' => function ($q) {
                    $q->whereIn('status', [Ticket::STATUS_RESOLVED, Ticket::STATUS_CLOSED]);
                },
            ])
            ->addSelect([
                'latest_ticket_at' => Ticket::selectRaw('MAX(updated_at)')
                    ->whereColumn('project_id', 'projects.id'),
            ])
            ->firstOrFail();

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:projects,name,'.$project->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'default_assignee_id' => 'nullable|exists:users,id',
            'visibility' => 'nullable|in:private,team,public',
            'ticket_prefix' => 'nullable|string|max:10',
            'color' => 'nullable|regex:/^#?[0-9a-fA-F]{6}$/',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
            'start_date' => $validated['start_date'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'default_assignee_id' => $validated['default_assignee_id'] ?? null,
            'visibility' => $validated['visibility'] ?? ($project->visibility ?? 'team'),
            'ticket_prefix' => $validated['ticket_prefix'] ?? $project->ticket_prefix,
            'color' => isset($validated['color'])
                ? (str_starts_with($validated['color'], '#') ? $validated['color'] : '#'.$validated['color'])
                : $project->color,
            'priority' => $validated['priority'] ?? ($project->priority ?? 'medium'),
        ]);

        return redirect()->route('projects.show', $project->slug)
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }
}
