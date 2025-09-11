<?php

use App\Http\Controllers\Api\ProjectsController;
use App\Http\Controllers\Api\TicketsController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('api.version', 'v1'))
    ->middleware(['api', 'throttle:api', 'token.admin'])
    ->name('api.')
    ->group(function (): void {
        Route::apiResource('projects', ProjectsController::class)->parameters([
            'projects' => 'project:id',
        ]);
        Route::apiResource('tickets', TicketsController::class);
    });
