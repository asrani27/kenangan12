<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as MaintenanceMode;

class CheckMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Allow access to maintenance page and login
        if ($request->is('maintenance*') || $request->is('login') || $request->is('api/*')) {
            return $next($request);
        }

        // Check if maintenance mode is enabled
        $isDown = File::exists(storage_path('framework/down'));

        // If maintenance mode is enabled
        if ($isDown) {
            // Allow access to maintenance control routes for authenticated users
            if ($request->user() && $request->is('maintenance/*')) {
                return $next($request);
            }

            // Return maintenance page instead of redirect
            return response()->view('maintenance', [], 503);
        }

        return $next($request);
    }
}
