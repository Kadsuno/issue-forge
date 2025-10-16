<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Ticket */
final class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $statusObject = $this->getCurrentStatusObject();
        $project = $this->whenLoaded('project', fn () => $this->project);

        // Get available transitions based on user permissions
        $availableTransitions = [];
        if ($request->user() && $project) {
            $workflowService = new \App\Services\WorkflowService;
            $availableTransitions = $workflowService->getStatesForUser($request->user(), $project)
                ->map(fn ($state) => [
                    'slug' => $state->slug,
                    'label' => $state->label,
                    'color' => $state->color,
                ])
                ->toArray();
        }

        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'status_details' => $statusObject ? WorkflowStateResource::make($statusObject) : null,
            'priority' => $this->priority,
            'available_transitions' => $availableTransitions,
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
