<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectList extends Component
{
    use WithPagination;

    public $showCreateForm = false;
    public $search = '';

    // Form fields
    public $name = '';
    public $description = '';
    public $is_active = true;

    /**
     * Render the project list with useful aggregates for the list view.
     */
    public function render()
    {
        $projects = Project::with(['user', 'defaultAssignee'])
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
                // Latest ticket activity timestamp per project
                'latest_ticket_at' => Ticket::selectRaw('MAX(updated_at)')
                    ->whereColumn('project_id', 'projects.id'),
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.project-list', compact('projects'));
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->resetForm();
        }
    }

    public function createProject()
    {
        $this->validate([
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'user_id' => Auth::id(),
        ]);

        $this->resetForm();
        $this->showCreateForm = false;
        session()->flash('message', 'Project created successfully!');
    }

    public function toggleProjectStatus($projectId)
    {
        $project = Project::findOrFail($projectId);
        $project->update(['is_active' => !$project->is_active]);

        session()->flash('message', 'Project status updated!');
    }

    private function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->is_active = true;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
