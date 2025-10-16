<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

final class TicketUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Get the ticket from route parameter (route model binding)
        $ticket = $this->route('ticket');

        if (! $ticket instanceof Ticket) {
            return false;
        }

        // If there's a user context, check policy
        if ($this->user()) {
            return $this->user()->can('update', $ticket);
        }

        // If no user but we got here, the admin token middleware passed - allow
        return true;
    }

    public function rules(): array
    {
        $ticket = $this->route('ticket');
        $projectId = $this->input('project_id', $ticket?->project_id);
        $validStatuses = $this->getValidStatuses($projectId);

        return [
            'project_id' => ['sometimes', 'integer', 'exists:projects,id'],
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:'.implode(',', $validStatuses)],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
        ];
    }

    /**
     * Get valid status values for the project
     */
    private function getValidStatuses(?int $projectId): array
    {
        if (! $projectId) {
            return ['open', 'in_progress', 'resolved', 'closed'];
        }

        $project = \App\Models\Project::find($projectId);
        if (! $project) {
            return ['open', 'in_progress', 'resolved', 'closed'];
        }

        return $project->getAvailableStates()->pluck('slug')->toArray();
    }
}
