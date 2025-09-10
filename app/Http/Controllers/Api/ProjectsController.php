<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProjectStoreRequest;
use App\Http\Requests\Api\ProjectUpdateRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class ProjectsController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $query = Project::query();

        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%");
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

        return ProjectCollection::make($query->paginate());
    }

    public function store(ProjectStoreRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return ProjectResource::make($project)->response()->setStatusCode(201);
    }

    public function show(Project $project): ProjectResource
    {
        return ProjectResource::make($project);
    }

    public function update(ProjectUpdateRequest $request, Project $project): ProjectResource
    {
        $project->update($request->validated());

        return ProjectResource::make($project);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }
}


