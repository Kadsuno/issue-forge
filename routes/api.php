<?php

use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TicketsController;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::prefix(config('api.version', 'v1'))
    ->middleware(['api', 'throttle:api', 'token.admin'])
    ->group(function (): void {
        // Bind project parameter by numeric ID to avoid slug-based binding from web.
        Route::bind('project', function ($value) {
            return Project::query()->findOrFail((int) $value);
        });

        Route::apiResource('projects', ProjectsController::class);
        Route::apiResource('tickets', TicketsController::class);
    });


