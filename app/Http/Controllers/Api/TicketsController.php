<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TicketStoreRequest;
use App\Http\Requests\Api\TicketUpdateRequest;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;

final class TicketsController extends Controller
{
    public function index(): TicketCollection
    {
        $query = Ticket::query()->with('project');

        if ($projectId = request('project_id')) {
            $query->where('project_id', (int) $projectId);
        }

        if ($status = request('status')) {
            $query->where('status', $status);
        }

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (($sort = request('sort')) !== null) {
            foreach (explode(',', $sort) as $segment) {
                $direction = str_starts_with($segment, '-') ? 'desc' : 'asc';
                $column = ltrim($segment, '-');
                $query->orderBy($column, $direction);
            }
        } else {
            $query->latest('id');
        }

        return TicketCollection::make($query->paginate());
    }

    public function store(TicketStoreRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // If no user_id provided, use the first available user (for API requests)
        if (! isset($validated['user_id'])) {
            $validated['user_id'] = \App\Models\User::first()?->id ?? 1;
        }

        $ticket = Ticket::create($validated);

        return TicketResource::make($ticket->load('project'))->response()->setStatusCode(201);
    }

    public function show(string $id): TicketResource
    {
        $ticket = Ticket::with('project')->findOrFail((int) $id);

        return TicketResource::make($ticket);
    }

    public function update(TicketUpdateRequest $request, Ticket $ticket): TicketResource
    {
        $ticket->update($request->validated());

        return TicketResource::make($ticket->load('project'));
    }

    public function destroy(string $id): JsonResponse
    {
        $ticket = Ticket::findOrFail((int) $id);
        $ticket->delete();

        return response()->json(null, 204);
    }
}
