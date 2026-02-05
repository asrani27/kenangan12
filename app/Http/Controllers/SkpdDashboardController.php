<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Skpd;
use App\Models\Pptk;
use App\Models\User;
use App\Models\Role;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SkpdDashboardController extends Controller
{
    /**
     * Display RFK (Rencana Fisik dan Keuangan) page.
     *
     * @return \Illuminate\View\View
     */
    public function rfkIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        return view('skpd.rfk.index', compact('skpd'));
    }

    /**
     * Display SKPD dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        // Get unique years from programs
        $years = Program::where('kode_skpd', $skpd->kode_skpd)
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Set default year to current year if no year parameter is provided
        $currentYear = date('Y');
        $selectedYear = $request->has('year') && $request->year !== '' 
            ? $request->year 
            : $currentYear;

        // Get statistics filtered by year
        $queryPrograms = Program::where('kode_skpd', $skpd->kode_skpd);
        if ($selectedYear) {
            $queryPrograms->where('tahun', $selectedYear);
        }
        $totalPrograms = $queryPrograms->count();

        $queryKegiatan = Kegiatan::whereIn('kode_program', 
            Program::where('kode_skpd', $skpd->kode_skpd)
                ->when($selectedYear, function($query) use ($selectedYear) {
                    return $query->where('tahun', $selectedYear);
                })
                ->pluck('kode'));
        $totalKegiatan = $queryKegiatan->count();

        $querySubkegiatan = SubKegiatan::whereIn('kode_kegiatan',
            Kegiatan::whereIn('kode_program',
                Program::where('kode_skpd', $skpd->kode_skpd)
                    ->when($selectedYear, function($query) use ($selectedYear) {
                        return $query->where('tahun', $selectedYear);
                    })
                    ->pluck('kode'))->pluck('kode'));
        $totalSubkegiatan = $querySubkegiatan->count();
        
        // Note: Uraian data might be in a different table, for now using 0
        $totalUraian = 0;

        return view('skpd.dashboard', compact(
            'skpd',
            'totalPrograms',
            'totalKegiatan',
            'totalSubkegiatan',
            'totalUraian',
            'years',
            'selectedYear'
        ));
    }

    /**
     * Display the user profile page.
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        return view('skpd.profile');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        // Validate the request
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Kata sandi saat ini tidak benar.'
            ]);
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Kata sandi berhasil diubah!');
    }

    /**
     * Display a listing of the bidang.
     *
     * @return \Illuminate\View\View
     */
    public function bidangIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $bidangs = Bidang::where('skpd_id', $skpd->id)->get();

        return view('skpd.bidang.index', compact('bidangs', 'skpd'));
    }

    /**
     * Show the form for creating a new bidang.
     *
     * @return \Illuminate\View\View
     */
    public function bidangCreate()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        return view('skpd.bidang.create', compact('skpd'));
    }

    /**
     * Store a newly created bidang in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bidangStore(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama Bidang wajib diisi.',
        ]);

        Bidang::create([
            'nama' => $request->nama,
            'skpd_id' => $skpd->id,
        ]);

        return redirect()->route('skpd.bidang.index')
            ->with('success', 'Bidang berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified bidang.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function bidangEdit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $bidang = Bidang::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();

        return view('skpd.bidang.edit', compact('bidang', 'skpd'));
    }

    /**
     * Update the specified bidang in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bidangUpdate(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $bidang = Bidang::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama Bidang wajib diisi.',
        ]);

        $bidang->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('skpd.bidang.index')
            ->with('success', 'Bidang berhasil diperbarui!');
    }

    /**
     * Remove the specified bidang from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bidangDestroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $bidang = Bidang::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();
        $bidang->delete();

        return redirect()->route('skpd.bidang.index')
            ->with('success', 'Bidang berhasil dihapus!');
    }

    /**
     * Display a listing of the pptk.
     *
     * @return \Illuminate\View\View
     */
    public function pptkIndex()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $pptks = Pptk::where('skpd_id', $skpd->id)
            ->with(['bidang', 'user'])
            ->get();

        $bidangs = Bidang::where('skpd_id', $skpd->id)->get();

        return view('skpd.pptk.index', compact('pptks', 'bidangs', 'skpd'));
    }

    /**
     * Show the form for creating a new pptk.
     *
     * @return \Illuminate\View\View
     */
    public function pptkCreate()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $bidangs = Bidang::where('skpd_id', $skpd->id)->get();

        return view('skpd.pptk.create', compact('skpd', 'bidangs'));
    }

    /**
     * Store a newly created pptk in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pptkStore(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $request->validate([
            'nip_pptk' => 'required|string|max:50|unique:pptk,nip_pptk',
            'nama_pptk' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidang,id',
        ], [
            'nip_pptk.required' => 'NIP PPTK wajib diisi.',
            'nip_pptk.unique' => 'NIP PPTK sudah digunakan.',
            'nama_pptk.required' => 'Nama PPTK wajib diisi.',
            'bidang_id.required' => 'Bidang wajib dipilih.',
            'bidang_id.exists' => 'Bidang tidak ditemukan.',
        ]);

        Pptk::create([
            'nip_pptk' => $request->nip_pptk,
            'nama_pptk' => $request->nama_pptk,
            'skpd_id' => $skpd->id,
            'bidang_id' => $request->bidang_id,
        ]);

        return redirect()->route('skpd.pptk.index')
            ->with('success', 'PPTK berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified pptk.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function pptkEdit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $pptk = Pptk::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();
        $bidangs = Bidang::where('skpd_id', $skpd->id)->get();

        return view('skpd.pptk.edit', compact('pptk', 'bidangs', 'skpd'));
    }

    /**
     * Update the specified pptk in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pptkUpdate(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $pptk = Pptk::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();

        $request->validate([
            'nip_pptk' => 'required|string|max:50|unique:pptk,nip_pptk,' . $id,
            'nama_pptk' => 'required|string|max:255',
            'bidang_id' => 'required|exists:bidang,id',
        ], [
            'nip_pptk.required' => 'NIP PPTK wajib diisi.',
            'nip_pptk.unique' => 'NIP PPTK sudah digunakan.',
            'nama_pptk.required' => 'Nama PPTK wajib diisi.',
            'bidang_id.required' => 'Bidang wajib dipilih.',
            'bidang_id.exists' => 'Bidang tidak ditemukan.',
        ]);

        $pptk->update([
            'nip_pptk' => $request->nip_pptk,
            'nama_pptk' => $request->nama_pptk,
            'bidang_id' => $request->bidang_id,
        ]);

        return redirect()->route('skpd.pptk.index')
            ->with('success', 'PPTK berhasil diperbarui!');
    }

    /**
     * Remove the specified pptk from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pptkDestroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $pptk = Pptk::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();
        $pptk->delete();

        return redirect()->route('skpd.pptk.index')
            ->with('success', 'PPTK berhasil dihapus!');
    }

    /**
     * Create user for pptk.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pptkCreateUser($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $pptk = Pptk::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();

        // Check if user already exists
        if ($pptk->user) {
            return back()->with('error', 'PPTK sudah memiliki user.');
        }

        // Create username from NIP
        $username = $pptk->nip_pptk;

        // Check if username already exists
        $existingUser = User::where('username', $username)->first();

        if ($existingUser) {
            // Link existing user to pptk
            $pptk->user_id = $existingUser->id;
            $pptk->save();

            return redirect()->route('skpd.pptk.index')
                ->with('success', 'User berhasil ditautkan ke PPTK. Username: ' . $username);
        }

        // Find PPTK role
        $pptkRole = Role::where('name', 'pptk')->first();

        if (!$pptkRole) {
            return back()->with('error', 'Role PPTK tidak ditemukan. Silakan hubungi administrator.');
        }

        // Create user
        $newUser = User::create([
            'name' => $pptk->nama_pptk,
            'username' => $username,
            'password' => Hash::make('pptk123456'), // Default password
        ]);

        // Assign PPTK role using many-to-many relationship
        $newUser->roles()->attach($pptkRole->id);

        // Link user to pptk
        $pptk->user_id = $newUser->id;
        $pptk->save();

        return redirect()->route('skpd.pptk.index')
            ->with('success', 'User berhasil dibuat untuk PPTK. Username: ' . $username . ', Password: pptk123456');
    }

    /**
     * Reset password for pptk user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pptkResetPassword($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $pptk = Pptk::where('id', $id)->where('skpd_id', $skpd->id)->firstOrFail();

        if (!$pptk->user) {
            return back()->with('error', 'PPTK belum memiliki user.');
        }

        // Reset password to default
        $pptk->user->password = Hash::make('pptk123456');
        $pptk->user->save();

        return redirect()->route('skpd.pptk.index')
            ->with('success', 'Password berhasil direset. Password default: pptk123456');
    }

    /**
     * Display a listing of the program.
     *
     * @return \Illuminate\View\View
     */
    public function programIndex(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        // Get unique years from programs
        $years = Program::where('kode_skpd', $skpd->kode_skpd)
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Set default year to current year if no year parameter is provided
        $currentYear = date('Y');
        $selectedYear = $request->has('year') && $request->year !== '' 
            ? $request->year 
            : $currentYear;

        // Get PPTK data
        $pptks = Pptk::where('skpd_id', $skpd->id)->get();

        // Build query with year filter
        $query = Program::where('kode_skpd', $skpd->kode_skpd);

        if ($selectedYear) {
            $query->where('tahun', $selectedYear);
        }

        $programs = $query->with('kegiatan.sub_kegiatan')
            ->get();

        return view('skpd.program.index', compact('programs', 'skpd', 'years', 'selectedYear', 'pptks'));
    }

    /**
     * Show the form for creating a new program.
     *
     * @return \Illuminate\View\View
     */
    public function programCreate()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        return view('skpd.program.create', compact('skpd'));
    }

    /**
     * Store a newly created program in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function programStore(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $request->validate([
            'tahun' => 'required|string|max:4',
            'kode' => 'required|string|max:50|unique:program,kode,null,id,kode_skpd,' . $skpd->kode_skpd . ',tahun,' . $request->tahun,
            'nama' => 'required|string|max:255',
        ], [
            'tahun.required' => 'Tahun wajib diisi.',
            'kode.required' => 'Kode Program wajib diisi.',
            'kode.unique' => 'Kode Program sudah digunakan untuk tahun dan SKPD ini.',
            'nama.required' => 'Nama Program wajib diisi.',
        ]);

        Program::create([
            'tahun' => $request->tahun,
            'kode_skpd' => $skpd->kode_skpd,
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('skpd.program.index')
            ->with('success', 'Data Rekening Belanja berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified program.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function programEdit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $program = Program::where('id', $id)->where('kode_skpd', $skpd->kode_skpd)->firstOrFail();

        return view('skpd.program.edit', compact('program', 'skpd'));
    }

    /**
     * Update the specified program in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function programUpdate(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $program = Program::where('id', $id)->where('kode_skpd', $skpd->kode_skpd)->firstOrFail();

        $request->validate([
            'tahun' => 'required|string|max:4',
            'kode' => 'required|string|max:50|unique:program,kode,' . $id . ',id,kode_skpd,' . $skpd->kode_skpd . ',tahun,' . $request->tahun,
            'nama' => 'required|string|max:255',
        ], [
            'tahun.required' => 'Tahun wajib diisi.',
            'kode.required' => 'Kode Program wajib diisi.',
            'kode.unique' => 'Kode Program sudah digunakan untuk tahun dan SKPD ini.',
            'nama.required' => 'Nama Program wajib diisi.',
        ]);

        $program->update([
            'tahun' => $request->tahun,
            'kode_skpd' => $skpd->kode_skpd,
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        return redirect()->route('skpd.program.index')
            ->with('success', 'Data Rekening Belanja berhasil diperbarui!');
    }

    /**
     * Remove the specified program from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function programDestroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return back()->with('error', 'Data SKPD tidak ditemukan.');
        }

        $program = Program::where('id', $id)->where('kode_skpd', $skpd->kode_skpd)->firstOrFail();
        $program->delete();

        return redirect()->route('skpd.program.index')
            ->with('success', 'Data Rekening Belanja berhasil dihapus!');
    }

    /**
     * Update PPTK for sub-kegiatan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSubkegiatanPptk(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        $user = Auth::user();
        $skpd = Skpd::where('user_id', $user->id)->first();

        if (!$skpd) {
            return response()->json(['success' => false, 'message' => 'Data SKPD tidak ditemukan.'], 404);
        }

        $request->validate([
            'subkegiatan_id' => 'required|integer',
            'nip_pptk' => 'nullable|string|max:50',
        ]);

        $subkegiatan = SubKegiatan::find($request->subkegiatan_id);

        if (!$subkegiatan) {
            return response()->json(['success' => false, 'message' => 'Sub-kegiatan tidak ditemukan.'], 404);
        }

        // Verify the subkegiatan belongs to the user's SKPD
        $kegiatan = Kegiatan::where('kode', $subkegiatan->kode_kegiatan)->first();
        $program = Program::where('kode', $kegiatan->kode_program)->first();

        if ($program->kode_skpd !== $skpd->kode_skpd) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses ke sub-kegiatan ini.'], 403);
        }

        // Update PPTK
        $subkegiatan->nip_pptk = $request->nip_pptk;
        $subkegiatan->save();

        return response()->json(['success' => true, 'message' => 'PPTK berhasil diperbarui!']);
    }
}
