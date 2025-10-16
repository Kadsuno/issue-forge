<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class WorkflowStateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'label' => $this->label,
            'description' => $this->description,
            'color' => $this->color,
            'icon' => $this->icon,
            'is_closed' => $this->is_closed,
            'is_predefined' => $this->is_predefined,
            'order' => $this->order,
            'project_id' => $this->project_id,
        ];
    }
}
