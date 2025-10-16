<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkflowStateStoreRequest;
use App\Http\Requests\WorkflowStateUpdateRequest;
use App\Models\Project;
use App\Models\WorkflowState;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

final class WorkflowStateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:workflow.manage']);
    }

    /**
     * Display a listing of workflow states
     */
    public function index(Request $request): View
    {
        $projectId = $request->get('project_id');

        $query = WorkflowState::query()->orderBy('order')->orderBy('id');

        if ($projectId) {
            $query->where('project_id', $projectId);
        } else {
            $query->whereNull('project_id');
        }

        $states = $query->get();
        $projects = Project::orderBy('name')->get();

        return view('admin.workflows.index', compact('states', 'projects', 'projectId'));
    }

    /**
     * Show the form for creating a new workflow state
     */
    public function create(Request $request): View
    {
        $projects = Project::orderBy('name')->get();
        $projectId = $request->get('project_id');

        return view('admin.workflows.create', compact('projects', 'projectId'));
    }

    /**
     * Store a newly created workflow state
     */
    public function store(WorkflowStateStoreRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_predefined'] = false;

        $state = WorkflowState::create($validated);

        return redirect()
            ->route('admin.workflows.index', ['project_id' => $state->project_id])
            ->with('success', 'Workflow state created successfully.');
    }

    /**
     * Show the form for editing a workflow state
     */
    public function edit(WorkflowState $workflow): View
    {
        $projects = Project::orderBy('name')->get();
        $roles = Role::all();
        $stateRoles = $workflow->roles->pluck('id')->toArray();

        return view('admin.workflows.edit', compact('workflow', 'projects', 'roles', 'stateRoles'));
    }

    /**
     * Update the specified workflow state
     */
    public function update(WorkflowStateUpdateRequest $request, WorkflowState $workflow): RedirectResponse
    {
        $validated = $request->validated();

        $workflow->update($validated);

        // Update role permissions
        if ($request->has('roles')) {
            $roleData = [];
            foreach ((array) $request->input('roles', []) as $roleId) {
                $roleData[$roleId] = ['can_set_to' => true];
            }
            $workflow->roles()->sync($roleData);
        }

        return redirect()
            ->route('admin.workflows.index', ['project_id' => $workflow->project_id])
            ->with('success', 'Workflow state updated successfully.');
    }

    /**
     * Remove the specified workflow state
     */
    public function destroy(WorkflowState $workflow): RedirectResponse
    {
        if ($workflow->is_predefined) {
            return redirect()
                ->route('admin.workflows.index')
                ->with('error', 'Cannot delete predefined workflow states.');
        }

        $projectId = $workflow->project_id;
        $workflow->delete();

        return redirect()
            ->route('admin.workflows.index', ['project_id' => $projectId])
            ->with('success', 'Workflow state deleted successfully.');
    }
}

