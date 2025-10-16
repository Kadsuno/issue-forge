<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class WorkflowStateStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\WorkflowState::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['nullable', 'string', 'max:50', Rule::unique('workflow_states', 'slug')->where('project_id', $this->input('project_id'))],
            'label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_closed' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
        ];
    }
}
