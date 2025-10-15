<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

final class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Get the project from route parameter (route model binding)
        $project = $this->route('project');

        if (! $project instanceof Project) {
            return false;
        }

        // If there's a user context, check policy
        if ($this->user()) {
            return $this->user()->can('update', $project);
        }

        // If no user but we got here, the admin token middleware passed - allow
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:120'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:active,archived'],
        ];
    }
}
