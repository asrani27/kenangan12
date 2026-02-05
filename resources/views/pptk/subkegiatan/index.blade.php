@extends('layouts.app')

@section('title', 'Subkegiatan - KENANGAN')

@section('header-title', 'Subkegiatan Saya')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Subkegiatan Saya</h1>
        <p class="text-slate-400 text-sm mt-1">Daftar subkegiatan yang ditangani</p>
    </div>

    <!-- Year Filter -->
    @if($years->count() > 0)
    <form method="GET" action="{{ route('pptk.subkegiatan.index') }}" class="mt-4 sm:mt-0">
        <select name="year" id="year" onchange="this.form.submit()"
            class="bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <option value="">Semua Tahun</option>
            @foreach($years as $year)
            <option value="{{ $year }}" {{ $selectedYear==$year ? 'selected' : '' }}>
                {{ $year }}
            </option>
            @endforeach
        </select>
    </form>
    @endif
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="mb-6 p-4 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400">
    <div class="flex items-center">
        <i class="fas fa-exclamation-circle mr-2"></i>
        {{ session('error') }}
    </div>
</div>
@endif

<!-- Subkegiatan Table -->
@if($subkegiatan->count() > 0)
<div class="card-gradient rounded-xl p-6 border border-slate-700/50">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-slate-700/50">
                    <th class="pb-3 text-sm font-semibold text-slate-400 w-20">No</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400">Kode</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400">Nama Subkegiatan</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 hidden md:table-cell">Total DPA</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @foreach($subkegiatan as $index => $subkegiatanItem)
                <tr class="subkegiatan-row">
                    <td class="py-3 text-sm text-slate-300">{{ $index + 1 }}</td>
                    <td class="py-3 text-sm text-slate-300 font-mono">{{ $subkegiatanItem->kode }}</td>
                    <td class="py-3">
                        <div class="flex items-center">
                            <span class="text-sm text-white font-medium">{{ $subkegiatanItem->nama }}</span>
                        </div>
                    </td>
                    @php
                    $totalDpa = $subkegiatanItem->uraian
                    ->where('tahun', $subkegiatanItem->tahun)
                    ->where('kode_subkegiatan', $subkegiatanItem->kode)
                    ->sum('dpa');
                    @endphp
                    <td class="py-3 text-sm text-white font-semibold hidden md:table-cell">
                        @if($totalDpa > 0)
                        Rp {{ number_format($totalDpa, 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="py-3">
                        <div class="flex items-center justify-center space-x-2">
                            @if($subkegiatanItem->uraian->count() > 0)
                            <span class="text-xs text-slate-400" title="{{ $subkegiatanItem->uraian->count() }} Uraian">
                                <i class="fas fa-file-alt mr-1"></i>{{ $subkegiatanItem->uraian->count() }}
                            </span>
                            @endif
                            <a href="{{ route('pptk.uraian.create', ['subkegiatan_id' => $subkegiatanItem->id]) }}"
                                class="w-8 h-8 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white flex items-center justify-center hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/20"
                                title="Tambah Uraian">
                                <i class="fas fa-plus text-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>

                <!-- Uraian Rows -->
                @php
                $filteredUraian = $subkegiatanItem->uraian
                ->where('tahun', $subkegiatanItem->tahun)
                ->where('kode_subkegiatan', $subkegiatanItem->kode);
                @endphp
                @if($filteredUraian->count() > 0)
                @foreach($filteredUraian as $uraian)
                <tr class="bg-slate-800/30">
                    <td class="py-2 text-sm text-slate-400"></td>
                    <td class="py-2 pl-8 text-sm text-slate-300 font-mono text-xs">{{ $uraian->kode_rekening }}</td>
                    <td class="py-2 pl-8">
                        <div class="flex items-center">
                            <span class="text-sm text-slate-300">{{ $uraian->nama }}</span>
                        </div>
                    </td>
                    <td class="py-2 text-sm text-white font-semibold hidden md:table-cell">
                        @if(!empty($uraian->dpa))
                        Rp {{ number_format($uraian->dpa, 0, ',', '.') }}
                        @else
                        -
                        @endif
                    </td>
                    <td class="py-2">
                        <div class="flex items-center justify-center space-x-2">
                            <button type="button" onclick="openAnggaranKasModal({{ $uraian->id }}, '{{ $uraian->nama }}', {{ $uraian->dpa ?? 0 }}, {{ json_encode([
                                    'januari_keuangan' => $uraian->p_januari_keuangan ?? 0,
                                    'februari_keuangan' => $uraian->p_februari_keuangan ?? 0,
                                    'maret_keuangan' => $uraian->p_maret_keuangan ?? 0,
                                    'april_keuangan' => $uraian->p_april_keuangan ?? 0,
                                    'mei_keuangan' => $uraian->p_mei_keuangan ?? 0,
                                    'juni_keuangan' => $uraian->p_juni_keuangan ?? 0,
                                    'juli_keuangan' => $uraian->p_juli_keuangan ?? 0,
                                    'agustus_keuangan' => $uraian->p_agustus_keuangan ?? 0,
                                    'september_keuangan' => $uraian->p_september_keuangan ?? 0,
                                    'oktober_keuangan' => $uraian->p_oktober_keuangan ?? 0,
                                    'november_keuangan' => $uraian->p_november_keuangan ?? 0,
                                    'desember_keuangan' => $uraian->p_desember_keuangan ?? 0,
                                    'januari_fisik' => $uraian->p_januari_fisik ?? 0,
                                    'februari_fisik' => $uraian->p_februari_fisik ?? 0,
                                    'maret_fisik' => $uraian->p_maret_fisik ?? 0,
                                    'april_fisik' => $uraian->p_april_fisik ?? 0,
                                    'mei_fisik' => $uraian->p_mei_fisik ?? 0,
                                    'juni_fisik' => $uraian->p_juni_fisik ?? 0,
                                    'juli_fisik' => $uraian->p_juli_fisik ?? 0,
                                    'agustus_fisik' => $uraian->p_agustus_fisik ?? 0,
                                    'september_fisik' => $uraian->p_september_fisik ?? 0,
                                    'oktober_fisik' => $uraian->p_oktober_fisik ?? 0,
                                    'november_fisik' => $uraian->p_november_fisik ?? 0,
                                    'desember_fisik' => $uraian->p_desember_fisik ?? 0,
                                ]) }})"
                                class="w-8 h-8 rounded-lg bg-gradient-to-r from-amber-500 to-yellow-600 text-white flex items-center justify-center hover:from-amber-600 hover:to-yellow-700 transition-all shadow-lg shadow-amber-500/20"
                                title="Anggaran Kas">
                                <i class="fas fa-money-bill-wave text-sm"></i>
                            </button>
                            <a href="{{ route('pptk.uraian.edit', ['id' => $uraian->id]) }}"
                                class="w-8 h-8 rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 text-white flex items-center justify-center hover:from-blue-600 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/20"
                                title="Edit Uraian">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('pptk.uraian.destroy', ['id' => $uraian->id]) }}" method="POST"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus uraian ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-8 h-8 rounded-lg bg-gradient-to-r from-red-500 to-rose-600 text-white flex items-center justify-center hover:from-red-600 hover:to-rose-700 transition-all shadow-lg shadow-red-500/20"
                                    title="Hapus Uraian">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<!-- Empty State -->
<div class="card-gradient rounded-xl p-12 border border-slate-700/50">
    <div class="flex flex-col items-center justify-center">
        <div class="w-20 h-20 rounded-full bg-slate-700/50 flex items-center justify-center mb-6">
            <i class="fas fa-inbox text-3xl text-slate-500"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Belum Ada Subkegiatan</h3>
        <p class="text-slate-400 text-sm text-center max-w-md mb-6">
            Anda belum ditugaskan ke subkegiatan manapun.
            Silakan hubungi administrator SKPD untuk penugasan.
        </p>
        <a href="{{ route('pptk.dashboard') }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endif

<!-- Anggaran Kas Modal -->
<div id="anggaranKasModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeAnggaranKasModal()"></div>
    
    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="card-gradient relative transform overflow-hidden rounded-2xl border border-slate-700/50 text-left shadow-2xl transition-all w-full max-w-3xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="border-b border-slate-700/50 px-6 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white" id="modal-title">Anggaran Kas</h3>
                        <p class="text-sm text-slate-400 mt-1" id="modal-uraian-name">Nama Uraian</p>
                    </div>
                    <button type="button" onclick="closeAnggaranKasModal()" 
                        class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="px-6 py-6 overflow-y-auto flex-1">
                <input type="hidden" id="anggaranKasUraianId" name="uraian_id">
                <input type="hidden" id="anggaranKasDpa" name="dpa">
                
                <!-- Table Header -->
                <div class="grid grid-cols-3 gap-4 mb-4 px-2">
                    <div class="text-sm font-semibold text-slate-400">Bulan</div>
                    <div class="text-sm font-semibold text-slate-400">Rencana Keuangan</div>
                    <div class="text-sm font-semibold text-slate-400">Rencana Fisik (%)</div>
                </div>
                
                <!-- Month Rows -->
                <div class="space-y-3">
                    <!-- Januari -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Januari</div>
                        <input type="text" name="januari_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="januari_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Februari -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Februari</div>
                        <input type="text" name="februari_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="februari_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Maret -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Maret</div>
                        <input type="text" name="maret_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="maret_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- April -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">April</div>
                        <input type="text" name="april_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="april_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Mei -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Mei</div>
                        <input type="text" name="mei_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="mei_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Juni -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Juni</div>
                        <input type="text" name="juni_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="juni_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Juli -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Juli</div>
                        <input type="text" name="juli_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="juli_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Agustus -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Agustus</div>
                        <input type="text" name="agustus_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="agustus_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- September -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">September</div>
                        <input type="text" name="september_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="september_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Oktober -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Oktober</div>
                        <input type="text" name="oktober_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="oktober_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- November -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">November</div>
                        <input type="text" name="november_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="november_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Desember -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Desember</div>
                        <input type="text" name="desember_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="desember_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500">
                    </div>
                </div>
                
                <!-- Total Summary -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Sisa DPA -->
                    <div class="p-4 rounded-xl bg-amber-500/10 border border-amber-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-amber-400">Sisa DPA</span>
                            <span class="text-lg font-bold text-white" id="sisaDpa">Rp 0</span>
                        </div>
                    </div>
                    
                    <!-- Total Rencana Fisik -->
                    <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-400">Total Rencana Fisik</span>
                            <span class="text-lg font-bold text-white" id="totalFisik">0%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="border-t border-slate-700/50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse flex-shrink-0">
                <button type="button" onclick="closeAnggaranKasModal()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="saveAnggaranKas()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 text-white text-sm font-medium rounded-lg hover:from-amber-600 hover:to-yellow-700 transition-all shadow-lg shadow-amber-500/20">
                    <i class="fas fa-save mr-2"></i>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openAnggaranKasModal(uraianId, uraianName, dpa, existingData) {
    document.getElementById('anggaranKasUraianId').value = uraianId;
    document.getElementById('anggaranKasDpa').value = dpa;
    document.getElementById('modal-uraian-name').textContent = uraianName;
    document.getElementById('anggaranKasModal').classList.remove('hidden');
    
    // Load existing data into inputs
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    
    months.forEach(month => {
        const keuanganInput = document.querySelector(`input[name="${month}_keuangan"]`);
        const fisikInput = document.querySelector(`input[name="${month}_fisik"]`);
        
        // Load financial data
        if (keuanganInput && existingData && existingData[`${month}_keuangan`]) {
            const keuanganValue = existingData[`${month}_keuangan`];
            keuanganInput.setAttribute('data-raw-value', keuanganValue);
            keuanganInput.value = parseInt(keuanganValue).toLocaleString('id-ID');
        }
        
        // Load physical data
        if (fisikInput && existingData && existingData[`${month}_fisik`]) {
            fisikInput.value = existingData[`${month}_fisik`];
        }
    });
    
    calculateTotal();
    calculateFisik();
}

function closeAnggaranKasModal() {
    document.getElementById('anggaranKasModal').classList.add('hidden');
    // Reset all inputs
    const allInputs = document.querySelectorAll('#anggaranKasModal input[type="text"], #anggaranKasModal input[type="number"]');
    allInputs.forEach(input => {
        input.value = '';
        if (input.hasAttribute('data-raw-value')) {
            input.setAttribute('data-raw-value', '');
        }
    });
    // Reset totals
    document.getElementById('sisaDpa').textContent = 'Rp 0';
    document.getElementById('totalFisik').textContent = '0%';
}

function formatNumberInput(input) {
    // Remove all non-digit characters
    let value = input.value.replace(/\D/g, '');
    
    // Store raw value for calculations
    input.setAttribute('data-raw-value', value);
    
    // Format with thousand separators
    if (value) {
        value = parseInt(value).toLocaleString('id-ID');
    }
    
    input.value = value;
}

function calculateTotal() {
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    let totalKeuangan = 0;
    
    months.forEach(month => {
        const input = document.querySelector(`input[name="${month}_keuangan"]`);
        if (input && input.getAttribute('data-raw-value')) {
            totalKeuangan += parseInt(input.getAttribute('data-raw-value')) || 0;
        }
    });
    
    // Get DPA value
    const dpa = parseInt(document.getElementById('anggaranKasDpa').value) || 0;
    
    // Calculate Sisa DPA
    const sisaDpa = dpa - totalKeuangan;
    
    // Update display
    document.getElementById('sisaDpa').textContent = 'Rp ' + sisaDpa.toLocaleString('id-ID');
    
    // Change color if negative
    const sisaDpaElement = document.getElementById('sisaDpa');
    if (sisaDpa < 0) {
        sisaDpaElement.classList.remove('text-white');
        sisaDpaElement.classList.add('text-red-400');
    } else {
        sisaDpaElement.classList.remove('text-red-400');
        sisaDpaElement.classList.add('text-white');
    }
}

function calculateFisik() {
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    let totalFisik = 0;
    
    months.forEach(month => {
        const input = document.querySelector(`input[name="${month}_fisik"]`);
        if (input && input.value) {
            totalFisik += parseFloat(input.value) || 0;
        }
    });
    
    document.getElementById('totalFisik').textContent = totalFisik.toFixed(2) + '%';
}

function saveAnggaranKas() {
    const uraianId = document.getElementById('anggaranKasUraianId').value;
    
    if (!uraianId) {
        alert('ID Uraian tidak ditemukan');
        return;
    }
    
    // Calculate totals for validation
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    let totalKeuangan = 0;
    let totalFisik = 0;
    
    months.forEach(month => {
        const keuanganInput = document.querySelector(`input[name="${month}_keuangan"]`);
        const fisikInput = document.querySelector(`input[name="${month}_fisik"]`);
        
        totalKeuangan += keuanganInput && keuanganInput.getAttribute('data-raw-value') 
            ? parseInt(keuanganInput.getAttribute('data-raw-value')) || 0 
            : 0;
        totalFisik += fisikInput && fisikInput.value 
            ? parseFloat(fisikInput.value) || 0 
            : 0;
    });
    
    // Get DPA value
    const dpa = parseInt(document.getElementById('anggaranKasDpa').value) || 0;
    
    // Validation: Check if Sisa DPA is not 0
    if (totalKeuangan !== dpa) {
        alert(`Sisa DPA harus Rp 0. Saat ini total rencana keuangan adalah Rp ${totalKeuangan.toLocaleString('id-ID')} dari DPA Rp ${dpa.toLocaleString('id-ID')}.`);
        return;
    }
    
    // Validation: Check if Total Fisik is not 100%
    // Use a small epsilon for floating point comparison
    if (Math.abs(totalFisik - 100) > 0.01) {
        alert(`Total Rencana Fisik harus 100%. Saat ini total adalah ${totalFisik.toFixed(2)}%.`);
        return;
    }
    
    // Collect all form data
    const formData = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    months.forEach(month => {
        const keuanganInput = document.querySelector(`input[name="${month}_keuangan"]`);
        const fisikInput = document.querySelector(`input[name="${month}_fisik"]`);
        
        formData[`${month}_keuangan`] = keuanganInput && keuanganInput.getAttribute('data-raw-value') 
            ? parseInt(keuanganInput.getAttribute('data-raw-value')) || 0 
            : 0;
        formData[`${month}_fisik`] = fisikInput && fisikInput.value 
            ? parseFloat(fisikInput.value) || 0 
            : 0;
    });
    
    // Send AJAX request
    const saveButton = document.querySelector('button[onclick="saveAnggaranKas()"]');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    
    fetch(`/pptk/uraian/${uraianId}/anggaran-kas`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            alert(data.message);
            // Close modal
            closeAnggaranKasModal();
            // Reload page to show updated data
            window.location.reload();
        } else {
            alert('Terjadi kesalahan saat menyimpan data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
    })
    .finally(() => {
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    });
}
</script>
@endsection
