<?php

use App\Http\Controllers\Api\AttachmentController;
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
        Route::apiResource('tickets', TicketsController::class)->parameters([
            'tickets' => 'ticket:id',
        ]);

        // Attachments
        Route::post('attachments', [AttachmentController::class, 'store']);
        Route::get('{type}/{id}/attachments', [AttachmentController::class, 'index'])
            ->where('type', 'tickets|projects|comments');
        Route::get('attachments/{attachment}', [AttachmentController::class, 'show']);
        Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download']);
        Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy']);

        // Workflow States
        Route::get('workflows/states', [\App\Http\Controllers\Api\WorkflowStatesController::class, 'index']);
        Route::get('projects/{project}/workflows/states', [\App\Http\Controllers\Api\WorkflowStatesController::class, 'forProject']);
    });
