<?php

namespace App\Http\Controllers;

use App\Models\Pptk;
use App\Models\SubKegiatan;
use App\Models\Uraian;
use App\Models\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PptkDashboardController extends Controller
{
    /**
     * Display the PPTK dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();
        $pptk = Pptk::where('user_id', $user->id)->first();

        if (!$pptk) {
            return back()->with('error', 'Data PPTK tidak ditemukan.');
        }

        // Get statistics based on nip_pptk
        $totalSubkegiatan = SubKegiatan::where('nip_pptk', $pptk->nip_pptk)->count();
        
        // Get all subkegiatan codes for this PPTK
        $subkegiatanCodes = SubKegiatan::where('nip_pptk', $pptk->nip_pptk)
            ->pluck('kode');
        
        // Count total uraian for these subkegiatan
        $totalUraian = Uraian::whereIn('kode_subkegiatan', $subkegiatanCodes)->count();
        
        // Calculate total DPA
        $totalDPA = Uraian::whereIn('kode_subkegiatan', $subkegiatanCodes)->sum('dpa');

        return view('pptk.dashboard', compact(
            'totalSubkegiatan',
            'totalUraian',
            'totalDPA'
        ));
    }

    /**
     * Display target page for a subkegiatan.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function subkegiatanTarget($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $pptk = Pptk::where('user_id', $user->id)->first();

        if (!$pptk) {
            return back()->with('error', 'Data PPTK tidak ditemukan.');
        }

        $subkegiatan = SubKegiatan::findOrFail($id);

        // Verify that this subkegiatan belongs to the PPTK
        if ($subkegiatan->nip_pptk !== $pptk->nip_pptk) {
            return back()->with('error', 'Anda tidak memiliki akses ke subkegiatan ini.');
        }

        // Get uraian for this subkegiatan with their targets
        $uraian = Uraian::where('kode_subkegiatan', $subkegiatan->kode)
            ->where('tahun', $subkegiatan->tahun)
            ->with('targets')
            ->get();

        return view('pptk.subkegiatan.target', compact('subkegiatan', 'uraian'));
    }

    /**
     * Display a listing of the subkegiatan for PPTK.
     *
     * @return \Illuminate\View\View
     */
    public function subkegiatanIndex(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $pptk = Pptk::where('user_id', $user->id)->first();

        if (!$pptk) {
            return back()->with('error', 'Data PPTK tidak ditemukan.');
        }

        // Get unique years from subkegiatan
        $years = SubKegiatan::where('nip_pptk', $pptk->nip_pptk)
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Set default year to current year if no year parameter is provided
        $currentYear = date('Y');
        $selectedYear = $request->has('year') && $request->year !== ''
            ? $request->year
            : $currentYear;

        // Build query with year filter
        $query = SubKegiatan::where('nip_pptk', $pptk->nip_pptk)
            ->with(['kegiatan.program' => function ($query) use ($pptk) {
                $query->where('kode_skpd', $pptk->skpd->kode_skpd);
            }])
            ->whereHas('kegiatan.program', function ($query) use ($pptk) {
                $query->where('kode_skpd', $pptk->skpd->kode_skpd);
            });

        if ($selectedYear) {
            $query->where('tahun', $selectedYear);
        }

        $subkegiatan = $query->get();

        return view('pptk.subkegiatan.index', compact('subkegiatan', 'years', 'selectedYear'));
    }

    /**
     * Show the form for creating a new uraian.
     *
     * @param  int  $subkegiatan_id
     * @return \Illuminate\View\View
     */
    public function uraianCreate($subkegiatan_id)
    {
        $subkegiatan = SubKegiatan::findOrFail($subkegiatan_id);

        return view('pptk.uraian.create', compact('subkegiatan'));
    }

    /**
     * Store a newly created uraian in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uraianStore(Request $request)
    {
        $request->validate([
            'subkegiatan_id' => 'required|exists:subkegiatan,id',
            'kode_rekening' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'dpa' => 'nullable|numeric',
        ], [
            'subkegiatan_id.required' => 'Subkegiatan harus dipilih.',
            'subkegiatan_id.exists' => 'Subkegiatan tidak ditemukan.',
            'kode_rekening.required' => 'Kode rekening wajib diisi.',
            'nama.required' => 'Nama uraian wajib diisi.',
            'dpa.numeric' => 'DPA harus berupa angka.',
        ]);

        $subkegiatan = SubKegiatan::with('kegiatan.program')->findOrFail($request->subkegiatan_id);

        Uraian::create([
            'kode_skpd' => $subkegiatan->kegiatan->program->kode_skpd,
            'kode_program' => $subkegiatan->kegiatan->program->kode,
            'kode_kegiatan' => $subkegiatan->kegiatan->kode,
            'kode_subkegiatan' => $subkegiatan->kode,
            'tahun' => $subkegiatan->tahun,
            'kode_rekening' => $request->kode_rekening,
            'nama' => $request->nama,
            'dpa' => $request->dpa ?? 0,
        ]);

        return redirect()->route('pptk.subkegiatan.index')
            ->with('success', 'Uraian berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified uraian.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function uraianEdit($id)
    {
        $uraian = Uraian::findOrFail($id);

        return view('pptk.uraian.edit', compact('uraian'));
    }

    /**
     * Update the specified uraian in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uraianUpdate(Request $request, $id)
    {
        $request->validate([
            'kode_rekening' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'dpa' => 'nullable|numeric',
        ], [
            'kode_rekening.required' => 'Kode rekening wajib diisi.',
            'nama.required' => 'Nama uraian wajib diisi.',
            'dpa.numeric' => 'DPA harus berupa angka.',
        ]);

        $uraian = Uraian::findOrFail($id);

        $uraian->update([
            'kode_rekening' => $request->kode_rekening,
            'nama' => $request->nama,
            'dpa' => $request->dpa ?? 0,
        ]);

        return redirect()->route('pptk.subkegiatan.index')
            ->with('success', 'Uraian berhasil diperbarui!');
    }

    /**
     * Remove the specified uraian from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uraianDestroy($id)
    {
        $uraian = Uraian::findOrFail($id);
        $uraian->delete();

        return redirect()->route('pptk.subkegiatan.index')
            ->with('success', 'Uraian berhasil dihapus!');
    }

    /**
     * Save Anggaran Kas data for a uraian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAnggaranKas(Request $request, $id)
    {
        $request->validate([
            'januari_keuangan' => 'nullable|numeric',
            'februari_keuangan' => 'nullable|numeric',
            'maret_keuangan' => 'nullable|numeric',
            'april_keuangan' => 'nullable|numeric',
            'mei_keuangan' => 'nullable|numeric',
            'juni_keuangan' => 'nullable|numeric',
            'juli_keuangan' => 'nullable|numeric',
            'agustus_keuangan' => 'nullable|numeric',
            'september_keuangan' => 'nullable|numeric',
            'oktober_keuangan' => 'nullable|numeric',
            'november_keuangan' => 'nullable|numeric',
            'desember_keuangan' => 'nullable|numeric',
            'januari_fisik' => 'nullable|numeric',
            'februari_fisik' => 'nullable|numeric',
            'maret_fisik' => 'nullable|numeric',
            'april_fisik' => 'nullable|numeric',
            'mei_fisik' => 'nullable|numeric',
            'juni_fisik' => 'nullable|numeric',
            'juli_fisik' => 'nullable|numeric',
            'agustus_fisik' => 'nullable|numeric',
            'september_fisik' => 'nullable|numeric',
            'oktober_fisik' => 'nullable|numeric',
            'november_fisik' => 'nullable|numeric',
            'desember_fisik' => 'nullable|numeric',
        ]);

        $uraian = Uraian::findOrFail($id);

        // Update uraian with anggaran kas data
        $uraian->update([
            'p_januari_keuangan' => $request->januari_keuangan ?? 0,
            'p_februari_keuangan' => $request->februari_keuangan ?? 0,
            'p_maret_keuangan' => $request->maret_keuangan ?? 0,
            'p_april_keuangan' => $request->april_keuangan ?? 0,
            'p_mei_keuangan' => $request->mei_keuangan ?? 0,
            'p_juni_keuangan' => $request->juni_keuangan ?? 0,
            'p_juli_keuangan' => $request->juli_keuangan ?? 0,
            'p_agustus_keuangan' => $request->agustus_keuangan ?? 0,
            'p_september_keuangan' => $request->september_keuangan ?? 0,
            'p_oktober_keuangan' => $request->oktober_keuangan ?? 0,
            'p_november_keuangan' => $request->november_keuangan ?? 0,
            'p_desember_keuangan' => $request->desember_keuangan ?? 0,
            'p_januari_fisik' => $request->januari_fisik ?? 0,
            'p_februari_fisik' => $request->februari_fisik ?? 0,
            'p_maret_fisik' => $request->maret_fisik ?? 0,
            'p_april_fisik' => $request->april_fisik ?? 0,
            'p_mei_fisik' => $request->mei_fisik ?? 0,
            'p_juni_fisik' => $request->juni_fisik ?? 0,
            'p_juli_fisik' => $request->juli_fisik ?? 0,
            'p_agustus_fisik' => $request->agustus_fisik ?? 0,
            'p_september_fisik' => $request->september_fisik ?? 0,
            'p_oktober_fisik' => $request->oktober_fisik ?? 0,
            'p_november_fisik' => $request->november_fisik ?? 0,
            'p_desember_fisik' => $request->desember_fisik ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anggaran Kas berhasil disimpan!'
        ]);
    }

    /**
     * Save target data for a uraian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTarget(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'spesifikasi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'satuan' => 'required|string|max:50',
            'target_januari' => 'nullable|numeric',
            'target_februari' => 'nullable|numeric',
            'target_maret' => 'nullable|numeric',
            'target_april' => 'nullable|numeric',
            'target_mei' => 'nullable|numeric',
            'target_juni' => 'nullable|numeric',
            'target_juli' => 'nullable|numeric',
            'target_agustus' => 'nullable|numeric',
            'target_september' => 'nullable|numeric',
            'target_oktober' => 'nullable|numeric',
            'target_november' => 'nullable|numeric',
            'target_desember' => 'nullable|numeric',
        ], [
            'keterangan.required' => 'Keterangan wajib diisi.',
            'spesifikasi.required' => 'Spesifikasi wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'satuan.required' => 'Satuan wajib diisi.',
        ]);

        $uraian = Uraian::findOrFail($id);

        // Create new target
        Target::create([
            'uraian_id' => $uraian->id,
            'keterangan' => $request->keterangan,
            'spesifikasi' => $request->spesifikasi,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'target_januari' => $request->target_januari ?? 0,
            'target_februari' => $request->target_februari ?? 0,
            'target_maret' => $request->target_maret ?? 0,
            'target_april' => $request->target_april ?? 0,
            'target_mei' => $request->target_mei ?? 0,
            'target_juni' => $request->target_juni ?? 0,
            'target_juli' => $request->target_juli ?? 0,
            'target_agustus' => $request->target_agustus ?? 0,
            'target_september' => $request->target_september ?? 0,
            'target_oktober' => $request->target_oktober ?? 0,
            'target_november' => $request->target_november ?? 0,
            'target_desember' => $request->target_desember ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Target berhasil disimpan!'
        ]);
    }

    /**
     * Delete target data.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTarget($id)
    {
        $target = Target::findOrFail($id);
        $target->delete();

        return response()->json([
            'success' => true,
            'message' => 'Target berhasil dihapus!'
        ]);
    }

    /**
     * Get target data.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTarget($id)
    {
        $target = Target::findOrFail($id);

        return response()->json([
            'success' => true,
            'target' => $target
        ]);
    }

    /**
     * Update target data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTarget(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'spesifikasi' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:1',
            'satuan' => 'required|string|max:50',
            'target_januari' => 'nullable|numeric',
            'target_februari' => 'nullable|numeric',
            'target_maret' => 'nullable|numeric',
            'target_april' => 'nullable|numeric',
            'target_mei' => 'nullable|numeric',
            'target_juni' => 'nullable|numeric',
            'target_juli' => 'nullable|numeric',
            'target_agustus' => 'nullable|numeric',
            'target_september' => 'nullable|numeric',
            'target_oktober' => 'nullable|numeric',
            'target_november' => 'nullable|numeric',
            'target_desember' => 'nullable|numeric',
        ], [
            'keterangan.required' => 'Keterangan wajib diisi.',
            'spesifikasi.required' => 'Spesifikasi wajib diisi.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.numeric' => 'Jumlah harus berupa angka.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'satuan.required' => 'Satuan wajib diisi.',
        ]);

        $target = Target::findOrFail($id);

        // Update target
        $target->update([
            'keterangan' => $request->keterangan,
            'spesifikasi' => $request->spesifikasi,
            'jumlah' => $request->jumlah,
            'satuan' => $request->satuan,
            'target_januari' => $request->target_januari ?? 0,
            'target_februari' => $request->target_februari ?? 0,
            'target_maret' => $request->target_maret ?? 0,
            'target_april' => $request->target_april ?? 0,
            'target_mei' => $request->target_mei ?? 0,
            'target_juni' => $request->target_juni ?? 0,
            'target_juli' => $request->target_juli ?? 0,
            'target_agustus' => $request->target_agustus ?? 0,
            'target_september' => $request->target_september ?? 0,
            'target_oktober' => $request->target_oktober ?? 0,
            'target_november' => $request->target_november ?? 0,
            'target_desember' => $request->target_desember ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Target berhasil diperbarui!'
        ]);
    }

    /**
     * Display realisasi index page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function realisasiIndex(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $pptk = Pptk::where('user_id', $user->id)->first();

        if (!$pptk) {
            return back()->with('error', 'Data PPTK tidak ditemukan.');
        }

        // Get unique years from subkegiatan
        $years = SubKegiatan::where('nip_pptk', $pptk->nip_pptk)
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Set default year to current year if no year parameter is provided
        $currentYear = date('Y');
        $selectedYear = $request->has('year') && $request->year !== ''
            ? $request->year
            : $currentYear;

        // Build query with year filter
        $query = SubKegiatan::where('nip_pptk', $pptk->nip_pptk)
            ->with(['kegiatan.program' => function ($query) use ($pptk) {
                $query->where('kode_skpd', $pptk->skpd->kode_skpd);
            }])
            ->whereHas('kegiatan.program', function ($query) use ($pptk) {
                $query->where('kode_skpd', $pptk->skpd->kode_skpd);
            });

        if ($selectedYear) {
            $query->where('tahun', $selectedYear);
        }

        $subkegiatan = $query->get();

        return view('pptk.realisasi.index', compact('subkegiatan', 'years', 'selectedYear'));
    }

    /**
     * Display realisasi form for a subkegiatan.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function realisasiShow($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $pptk = Pptk::where('user_id', $user->id)->first();

        if (!$pptk) {
            return back()->with('error', 'Data PPTK tidak ditemukan.');
        }

        $subkegiatan = SubKegiatan::findOrFail($id);

        // Verify that this subkegiatan belongs to the PPTK
        if ($subkegiatan->nip_pptk !== $pptk->nip_pptk) {
            return back()->with('error', 'Anda tidak memiliki akses ke subkegiatan ini.');
        }

        // Get uraian for this subkegiatan with their targets
        $uraian = Uraian::where('kode_subkegiatan', $subkegiatan->kode)
            ->where('tahun', $subkegiatan->tahun)
            ->with('targets')
            ->get();

        return view('pptk.realisasi.show', compact('subkegiatan', 'uraian'));
    }

    /**
     * Save realisasi data for a uraian.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveRealisasi(Request $request)
    {
        $request->validate([
            'januari_keuangan' => 'nullable|numeric',
            'februari_keuangan' => 'nullable|numeric',
            'maret_keuangan' => 'nullable|numeric',
            'april_keuangan' => 'nullable|numeric',
            'mei_keuangan' => 'nullable|numeric',
            'juni_keuangan' => 'nullable|numeric',
            'juli_keuangan' => 'nullable|numeric',
            'agustus_keuangan' => 'nullable|numeric',
            'september_keuangan' => 'nullable|numeric',
            'oktober_keuangan' => 'nullable|numeric',
            'november_keuangan' => 'nullable|numeric',
            'desember_keuangan' => 'nullable|numeric',
            'januari_fisik' => 'nullable|numeric',
            'februari_fisik' => 'nullable|numeric',
            'maret_fisik' => 'nullable|numeric',
            'april_fisik' => 'nullable|numeric',
            'mei_fisik' => 'nullable|numeric',
            'juni_fisik' => 'nullable|numeric',
            'juli_fisik' => 'nullable|numeric',
            'agustus_fisik' => 'nullable|numeric',
            'september_fisik' => 'nullable|numeric',
            'oktober_fisik' => 'nullable|numeric',
            'november_fisik' => 'nullable|numeric',
            'desember_fisik' => 'nullable|numeric',
        ]);

        $uraian = Uraian::findOrFail($id);

        // Update uraian with realisasi data
        $uraian->update([
            'r_januari_keuangan' => $request->januari_keuangan ?? 0,
            'r_februari_keuangan' => $request->februari_keuangan ?? 0,
            'r_maret_keuangan' => $request->maret_keuangan ?? 0,
            'r_april_keuangan' => $request->april_keuangan ?? 0,
            'r_mei_keuangan' => $request->mei_keuangan ?? 0,
            'r_juni_keuangan' => $request->juni_keuangan ?? 0,
            'r_juli_keuangan' => $request->juli_keuangan ?? 0,
            'r_agustus_keuangan' => $request->agustus_keuangan ?? 0,
            'r_september_keuangan' => $request->september_keuangan ?? 0,
            'r_oktober_keuangan' => $request->oktober_keuangan ?? 0,
            'r_november_keuangan' => $request->november_keuangan ?? 0,
            'r_desember_keuangan' => $request->desember_keuangan ?? 0,
            'r_januari_fisik' => $request->januari_fisik ?? 0,
            'r_februari_fisik' => $request->februari_fisik ?? 0,
            'r_maret_fisik' => $request->maret_fisik ?? 0,
            'r_april_fisik' => $request->april_fisik ?? 0,
            'r_mei_fisik' => $request->mei_fisik ?? 0,
            'r_juni_fisik' => $request->juni_fisik ?? 0,
            'r_juli_fisik' => $request->juli_fisik ?? 0,
            'r_agustus_fisik' => $request->agustus_fisik ?? 0,
            'r_september_fisik' => $request->september_fisik ?? 0,
            'r_oktober_fisik' => $request->oktober_fisik ?? 0,
            'r_november_fisik' => $request->november_fisik ?? 0,
            'r_desember_fisik' => $request->desember_fisik ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Realisasi berhasil disimpan!'
        ]);
    }
}
