<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add security headers to all web responses
        $middleware->web(append: [
            App\Http\Middleware\SecurityHeaders::class,
        ]);

        // Spatie permission middleware aliases (use class-string to avoid static analysis errors)
        $middleware->alias([
            'role' => 'Spatie\\Permission\\Middlewares\\RoleMiddleware',
            'permission' => 'Spatie\\Permission\\Middlewares\\PermissionMiddleware',
            'role_or_permission' => 'Spatie\\Permission\\Middlewares\\RoleOrPermissionMiddleware',
            'token.admin' => App\Http\Middleware\AdminTokenMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
