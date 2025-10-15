<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class AttachmentResource extends JsonResource
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
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'human_file_size' => $this->human_file_size,
            'mime_type' => $this->mime_type,
            'url' => $this->url,
            'is_image' => $this->isImage(),
            'thumbnail_url' => $this->thumbnail_url,
            'file_icon' => $this->file_icon,
            'uploaded_by' => [
                'id' => $this->uploadedBy->id,
                'name' => $this->uploadedBy->name,
            ],
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
