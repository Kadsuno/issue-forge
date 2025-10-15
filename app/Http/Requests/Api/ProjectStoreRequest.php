<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

final class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // For API, if token middleware passed, allow access
        // The AdminTokenMiddleware has already validated the token
        // If there's a user context, check policy; otherwise, allow for admin token
        if ($this->user()) {
            return $this->user()->can('create', Project::class);
        }

        // If no user but we got here, the admin token middleware passed
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,archived'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
