<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;

final class TicketStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // For API, if token middleware passed, allow access
        // If there's a user context, check policy; otherwise, allow for admin token
        if ($this->user()) {
            return $this->user()->can('create', Ticket::class);
        }

        // If no user but we got here, the admin token middleware passed
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:open,in_progress,resolved,closed'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ];
    }
}
