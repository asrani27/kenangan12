<?php

namespace App\Http\Controllers;

use App\Models\Skpd;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SkpdController extends Controller
{
    /**
     * Display a listing of the SKPD.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $skpds = Skpd::all();

        return view('superadmin.skpd.index', compact('skpds'));
    }

    /**
     * Show the form for creating a new SKPD.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return view('superadmin.skpd.create');
    }

    /**
     * Store a newly created SKPD in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'kode_skpd' => 'required|string|max:50|unique:skpd,kode_skpd',
            'nama' => 'required|string|max:255',
        ], [
            'kode_skpd.required' => 'Kode SKPD wajib diisi.',
            'kode_skpd.unique' => 'Kode SKPD sudah digunakan.',
            'nama.required' => 'Nama SKPD wajib diisi.',
        ]);

        Skpd::create([
            'kode_skpd' => $request->kode_skpd,
            'nama' => $request->nama,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('superadmin.skpd.index')
            ->with('success', 'SKPD berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified SKPD.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $skpd = Skpd::findOrFail($id);

        return view('superadmin.skpd.edit', compact('skpd'));
    }

    /**
     * Update the specified SKPD in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $skpd = Skpd::findOrFail($id);

        $request->validate([
            'kode_skpd' => 'required|string|max:50|unique:skpd,kode_skpd,' . $id,
            'nama' => 'required|string|max:255',
        ], [
            'kode_skpd.required' => 'Kode SKPD wajib diisi.',
            'kode_skpd.unique' => 'Kode SKPD sudah digunakan.',
            'nama.required' => 'Nama SKPD wajib diisi.',
        ]);

        $skpd->update([
            'kode_skpd' => $request->kode_skpd,
            'nama' => $request->nama,
        ]);

        return redirect()->route('superadmin.skpd.index')
            ->with('success', 'SKPD berhasil diperbarui!');
    }

    /**
     * Create a user for the specified SKPD.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createUser($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $skpd = Skpd::findOrFail($id);

        if ($skpd->user_id) {
            return redirect()->route('superadmin.skpd.index')
                ->with('error', 'SKPD ini sudah memiliki user login.');
        }

        $skpdRole = Role::where('nama', 'skpd')->first();

        if (!$skpdRole) {
            return redirect()->route('superadmin.skpd.index')
                ->with('error', 'Role SKPD tidak ditemukan. Silakan hubungi administrator.');
        }

        $user = User::create([
            'username' => $skpd->kode_skpd,
            'name' => $skpd->nama,
            'password' => Hash::make('adminskpd'),
            'role_id' => $skpdRole->id,
        ]);

        $skpd->update(['user_id' => $user->id]);

        return redirect()->route('superadmin.skpd.index')
            ->with('success', 'User SKPD berhasil dibuat dengan username "' . $user->username . '" dan password "adminskpd"');
    }

    /**
     * Reset password for the SKPD user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $skpd = Skpd::findOrFail($id);

        if (!$skpd->user_id) {
            return redirect()->route('superadmin.skpd.index')
                ->with('error', 'SKPD ini belum memiliki user login.');
        }

        $user = User::findOrFail($skpd->user_id);
        $user->update([
            'password' => Hash::make('adminskpd'),
        ]);

        return redirect()->route('superadmin.skpd.index')
            ->with('success', 'Password untuk user "' . $user->username . '" berhasil direset menjadi "adminskpd"');
    }

    /**
     * Remove the specified SKPD from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $skpd = Skpd::findOrFail($id);
        $skpd->delete();

        return redirect()->route('superadmin.skpd.index')
            ->with('success', 'SKPD berhasil dihapus!');
    }
}
