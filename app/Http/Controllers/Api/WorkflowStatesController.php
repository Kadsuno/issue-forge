<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkflowStateResource;
use App\Models\Project;
use App\Models\WorkflowState;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class WorkflowStatesController extends Controller
{
    /**
     * Get all global workflow states
     */
    public function index(): AnonymousResourceCollection
    {
        $states = WorkflowState::global()->orderBy('order')->get();

        return WorkflowStateResource::collection($states);
    }

    /**
     * Get workflow states for a specific project
     */
    public function forProject(Project $project): AnonymousResourceCollection
    {
        $states = $project->getAvailableStates();

        return WorkflowStateResource::collection($states);
    }
}

