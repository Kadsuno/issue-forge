<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class WorkflowStateUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $state = $this->route('workflow');

        return $this->user()->can('update', $state);
    }

    public function rules(): array
    {
        $state = $this->route('workflow');

        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('workflow_states', 'slug')
                    ->ignore($state->id)
                    ->where('project_id', $state->project_id),
            ],
            'label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color' => ['required', 'string', 'max:20'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_closed' => ['nullable', 'boolean'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
