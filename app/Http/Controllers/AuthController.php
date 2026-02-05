<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate the login request
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        $credentials = $request->only('username', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Regenerate the session to prevent session fixation
            $request->session()->regenerate();

            // Get the authenticated user
            $user = Auth::user();
            $role = $user->roles()->first();

            // Redirect based on user role name
            if ($role && $role->name === 'superadmin') {
                return redirect()->route('superadmin.dashboard')
                    ->with('success', 'Anda berhasil masuk!');
            } elseif ($role && $role->name === 'skpd') {
                return redirect()->route('skpd.dashboard')
                    ->with('success', 'Anda berhasil masuk!');
            } elseif ($role && $role->name === 'pptk') {
                return redirect()->route('pptk.dashboard')
                    ->with('success', 'Anda berhasil masuk!');
            }

            // Default fallback
            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Anda berhasil masuk!');
        }

        // Authentication failed
        return back()
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => 'Username atau password yang Anda masukkan salah.',
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Anda berhasil keluar.');
    }
}