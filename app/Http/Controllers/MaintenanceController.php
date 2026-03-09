<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MaintenanceController extends Controller
{
    /**
     * Display the maintenance page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('maintenance');
    }

    /**
     * Enable maintenance mode.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enable()
    {
        // Create down file to enable maintenance mode
        File::put(storage_path('framework/down'), json_encode([
            'time' => time(),
            'message' => 'Sistem sedang dalam perbaikan',
            'retry' => 60,
            'allowed' => [],
        ]));

        return redirect()->back()->with('success', 'Maintenance mode diaktifkan.');
    }

    /**
     * Disable maintenance mode.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disable()
    {
        // Remove down file to disable maintenance mode
        if (File::exists(storage_path('framework/down'))) {
            File::delete(storage_path('framework/down'));
        }

        return redirect()->back()->with('success', 'Maintenance mode dinonaktifkan.');
    }

    /**
     * Check maintenance mode status.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        $isDown = File::exists(storage_path('framework/down'));

        return response()->json([
            'maintenance_mode' => $isDown,
        ]);
    }
}