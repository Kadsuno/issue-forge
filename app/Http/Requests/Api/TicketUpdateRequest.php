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
        return [
            'project_id' => ['sometimes', 'integer', 'exists:projects,id'],
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'in:open,in_progress,resolved,closed'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
        ];
    }
}
