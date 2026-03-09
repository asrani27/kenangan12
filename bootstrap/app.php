<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        
        // Override Laravel's built-in maintenance mode to use custom view
        $middleware->replace(
            \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
            \App\Http\Middleware\CheckMaintenance::class
        );
        
        // Register CheckMaintenance middleware as alias
        $middleware->alias([
            'maintenance' => \App\Http\Middleware\CheckMaintenance::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($e instanceof \Illuminate\Foundation\Http\Exceptions\MaintenanceModeException) {
                return response()->view('maintenance', [], 503);
            }
        });
    })->create();
