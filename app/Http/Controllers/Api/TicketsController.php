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
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TicketsController extends Controller
{
    public function index(): AnonymousResourceCollection
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
        $ticket = Ticket::create($request->validated());

        return TicketResource::make($ticket->load('project'))->response()->setStatusCode(201);
    }

    public function show(Ticket $ticket): TicketResource
    {
        return TicketResource::make($ticket->load('project'));
    }

    public function update(TicketUpdateRequest $request, Ticket $ticket): TicketResource
    {
        $ticket->update($request->validated());

        return TicketResource::make($ticket->load('project'));
    }

    public function destroy(Ticket $ticket): JsonResponse
    {
        $ticket->delete();

        return response()->json(null, 204);
    }
}


