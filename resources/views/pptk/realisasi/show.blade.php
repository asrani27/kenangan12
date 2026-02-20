@extends('layouts.app')

@section('title', 'Realisasi - KENANGAN')

@section('header-title', 'Realisasi Uraian')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Realisasi Uraian</h1>
        <p class="text-slate-400 text-sm mt-1">Daftar uraian untuk subkegiatan: {{ $subkegiatan->nama }}</p>
    </div>
    <a href="{{ route('pptk.realisasi.index') }}"
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
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

<!-- Subkegiatan Info Card -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h3 class="text-lg font-bold text-white mb-2">{{ $subkegiatan->nama }}</h3>
            <div class="flex flex-wrap gap-2 text-sm">
                <span class="text-slate-400">
                    <i class="fas fa-code mr-1"></i> {{ $subkegiatan->kode }}
                </span>
                <span class="text-slate-400">
                    <i class="fas fa-calendar mr-1"></i> {{ $subkegiatan->tahun }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Uraian List -->
@if($uraian->count() > 0)
@foreach($uraian as $uraianItem)
<!-- Uraian Card -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50 mb-6">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between mb-4">
        <div class="flex-1">
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3">
                    <i class="fas fa-file-alt text-xs text-white"></i>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white">{{ $uraianItem->nama }}</h4>
                    <span class="text-xs text-slate-400 font-mono">{{ $uraianItem->kode_rekening }}</span>
                </div>
            </div>
            @if(!empty($uraianItem->dpa))
            <div class="text-sm text-slate-400 ml-11">
                <span class="text-amber-400 font-semibold">DPA:</span> 
                Rp {{ number_format($uraianItem->dpa, 0, ',', '.') }}
            </div>
            @endif
        </div>
        <button type="button" onclick="openRealisasiModal({{ $uraianItem->id }}, {{ json_encode($uraianItem->nama) }}, {{ $uraianItem->dpa ?? 0 }}, {{ json_encode([
            'r_januari_keuangan' => $uraianItem->r_januari_keuangan ?? 0,
            'r_februari_keuangan' => $uraianItem->r_februari_keuangan ?? 0,
            'r_maret_keuangan' => $uraianItem->r_maret_keuangan ?? 0,
            'r_april_keuangan' => $uraianItem->r_april_keuangan ?? 0,
            'r_mei_keuangan' => $uraianItem->r_mei_keuangan ?? 0,
            'r_juni_keuangan' => $uraianItem->r_juni_keuangan ?? 0,
            'r_juli_keuangan' => $uraianItem->r_juli_keuangan ?? 0,
            'r_agustus_keuangan' => $uraianItem->r_agustus_keuangan ?? 0,
            'r_september_keuangan' => $uraianItem->r_september_keuangan ?? 0,
            'r_oktober_keuangan' => $uraianItem->r_oktober_keuangan ?? 0,
            'r_november_keuangan' => $uraianItem->r_november_keuangan ?? 0,
            'r_desember_keuangan' => $uraianItem->r_desember_keuangan ?? 0,
            'r_januari_fisik' => $uraianItem->r_januari_fisik ?? 0,
            'r_februari_fisik' => $uraianItem->r_februari_fisik ?? 0,
            'r_maret_fisik' => $uraianItem->r_maret_fisik ?? 0,
            'r_april_fisik' => $uraianItem->r_april_fisik ?? 0,
            'r_mei_fisik' => $uraianItem->r_mei_fisik ?? 0,
            'r_juni_fisik' => $uraianItem->r_juni_fisik ?? 0,
            'r_juli_fisik' => $uraianItem->r_juli_fisik ?? 0,
            'r_agustus_fisik' => $uraianItem->r_agustus_fisik ?? 0,
            'r_september_fisik' => $uraianItem->r_september_fisik ?? 0,
            'r_oktober_fisik' => $uraianItem->r_oktober_fisik ?? 0,
            'r_november_fisik' => $uraianItem->r_november_fisik ?? 0,
            'r_desember_fisik' => $uraianItem->r_desember_fisik ?? 0,
        ]) }})"
            class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/20">
            <i class="fas fa-chart-line mr-2"></i>
            Input Realisasi
        </button>
    </div>

    <!-- Targets List -->
    @if($uraianItem->targets->count() > 0)
    <div class="ml-11 overflow-x-auto mt-4">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-slate-700/50">
                    <th class="pb-3 text-sm font-semibold text-slate-400">Keterangan</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400">Spesifikasi</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center">Jumlah</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center">Satuan</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center">Total Target</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @foreach($uraianItem->targets as $target)
                <tr>
                    <td class="py-3 text-sm text-white">{{ $target->keterangan }}</td>
                    <td class="py-3 text-sm text-slate-300">{{ $target->spesifikasi }}</td>
                    <td class="py-3 text-sm text-white text-center">{{ $target->jumlah }}</td>
                    <td class="py-3 text-sm text-slate-300 text-center">{{ $target->satuan }}</td>
                    <td class="py-3 text-sm text-center">
                        <span class="inline-flex items-center px-2 py-1 bg-blue-500/10 border border-blue-500/30 rounded text-blue-400 text-sm font-medium">
                            {{ $target->target_januari + $target->target_februari + $target->target_maret + $target->target_april + $target->target_mei + $target->target_juni + $target->target_juli + $target->target_agustus + $target->target_september + $target->target_oktober + $target->target_november + $target->target_desember }} {{ $target->satuan }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="flex items-center justify-center space-x-2">
                            <button type="button" onclick="openRealisasiTargetModal({{ $target->id }}, {{ json_encode($target->keterangan) }}, {{ json_encode($target->satuan) }}, {{ json_encode([
                                'target_januari' => $target->target_januari ?? 0,
                                'target_februari' => $target->target_februari ?? 0,
                                'target_maret' => $target->target_maret ?? 0,
                                'target_april' => $target->target_april ?? 0,
                                'target_mei' => $target->target_mei ?? 0,
                                'target_juni' => $target->target_juni ?? 0,
                                'target_juli' => $target->target_juli ?? 0,
                                'target_agustus' => $target->target_agustus ?? 0,
                                'target_september' => $target->target_september ?? 0,
                                'target_oktober' => $target->target_oktober ?? 0,
                                'target_november' => $target->target_november ?? 0,
                                'target_desember' => $target->target_desember ?? 0,
                                'realisasi_januari' => $target->realisasi_januari ?? 0,
                                'realisasi_februari' => $target->realisasi_februari ?? 0,
                                'realisasi_maret' => $target->realisasi_maret ?? 0,
                                'realisasi_april' => $target->realisasi_april ?? 0,
                                'realisasi_mei' => $target->realisasi_mei ?? 0,
                                'realisasi_juni' => $target->realisasi_juni ?? 0,
                                'realisasi_juli' => $target->realisasi_juli ?? 0,
                                'realisasi_agustus' => $target->realisasi_agustus ?? 0,
                                'realisasi_september' => $target->realisasi_september ?? 0,
                                'realisasi_oktober' => $target->realisasi_oktober ?? 0,
                                'realisasi_november' => $target->realisasi_november ?? 0,
                                'realisasi_desember' => $target->realisasi_desember ?? 0,
                            ]) }})"
                                class="w-8 h-8 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 flex items-center justify-center hover:bg-green-500/20 transition-colors"
                                title="Input Realisasi Target">
                                <i class="fas fa-chart-line text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endforeach
@else
<!-- Empty State -->
<div class="card-gradient rounded-xl p-12 border border-slate-700/50">
    <div class="flex flex-col items-center justify-center">
        <div class="w-20 h-20 rounded-full bg-slate-700/50 flex items-center justify-center mb-6">
            <i class="fas fa-inbox text-3xl text-slate-500"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Belum Ada Uraian</h3>
        <p class="text-slate-400 text-sm text-center max-w-md mb-6">
            Subkegiatan ini belum memiliki uraian.
            Silakan tambahkan uraian terlebih dahulu.
        </p>
    </div>
</div>
@endif

<!-- Realisasi Modal -->
<div id="realisasiModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeRealisasiModal()"></div>
    
    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="card-gradient relative transform overflow-hidden rounded-2xl border border-slate-700/50 text-left shadow-2xl transition-all w-full max-w-3xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="border-b border-slate-700/50 px-6 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white" id="modal-title">Input Realisasi</h3>
                        <p class="text-sm text-slate-400 mt-1" id="modal-uraian-name">Nama Uraian</p>
                    </div>
                    <button type="button" onclick="closeRealisasiModal()" 
                        class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="px-6 py-6 overflow-y-auto flex-1">
                <input type="hidden" id="realisasiUraianId" name="uraian_id">
                <input type="hidden" id="realisasiDpa" name="dpa">
                
                <!-- Table Header -->
                <div class="grid grid-cols-3 gap-4 mb-4 px-2">
                    <div class="text-sm font-semibold text-slate-400">Bulan</div>
                    <div class="text-sm font-semibold text-slate-400">Realisasi Keuangan</div>
                    <div class="text-sm font-semibold text-slate-400">Realisasi Fisik (%)</div>
                </div>
                
                <!-- Month Rows -->
                <div class="space-y-3">
                    <!-- Januari -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Januari</div>
                        <input type="text" name="januari_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="januari_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Februari -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Februari</div>
                        <input type="text" name="februari_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="februari_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Maret -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Maret</div>
                        <input type="text" name="maret_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="maret_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- April -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">April</div>
                        <input type="text" name="april_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="april_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Mei -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Mei</div>
                        <input type="text" name="mei_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="mei_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Juni -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Juni</div>
                        <input type="text" name="juni_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="juni_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Juli -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Juli</div>
                        <input type="text" name="juli_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="juli_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Agustus -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Agustus</div>
                        <input type="text" name="agustus_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="agustus_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- September -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">September</div>
                        <input type="text" name="september_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="september_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Oktober -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Oktober</div>
                        <input type="text" name="oktober_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="oktober_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- November -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">November</div>
                        <input type="text" name="november_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="november_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                    
                    <!-- Desember -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Desember</div>
                        <input type="text" name="desember_keuangan" placeholder="0" oninput="formatNumberInput(this); calculateTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500" data-raw-value="">
                        <input type="number" name="desember_fisik" placeholder="0" min="0" max="100" step="0.01" oninput="calculateFisik()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500">
                    </div>
                </div>
                
                <!-- Total Summary -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Total Realisasi Keuangan -->
                    <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-green-400">Total Realisasi Keuangan</span>
                            <span class="text-lg font-bold text-white" id="totalKeuangan">Rp 0</span>
                        </div>
                    </div>
                    
                    <!-- Total Realisasi Fisik -->
                    <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-400">Total Realisasi Fisik</span>
                            <span class="text-lg font-bold text-white" id="totalFisik">0%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="border-t border-slate-700/50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse flex-shrink-0">
                <button type="button" onclick="closeRealisasiModal()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="saveRealisasi()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/20">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Realisasi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openRealisasiModal(uraianId, uraianName, dpa, existingData) {
    document.getElementById('realisasiUraianId').value = uraianId;
    document.getElementById('realisasiDpa').value = dpa;
    document.getElementById('modal-uraian-name').textContent = uraianName;
    document.getElementById('realisasiModal').classList.remove('hidden');
    
    // Load existing data into inputs
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    
    months.forEach(month => {
        const keuanganInput = document.querySelector(`input[name="${month}_keuangan"]`);
        const fisikInput = document.querySelector(`input[name="${month}_fisik"]`);
        
        // Load financial data
        if (keuanganInput && existingData && existingData[`r_${month}_keuangan`]) {
            const keuanganValue = existingData[`r_${month}_keuangan`];
            keuanganInput.setAttribute('data-raw-value', keuanganValue);
            keuanganInput.value = parseInt(keuanganValue).toLocaleString('id-ID');
        }
        
        // Load physical data
        if (fisikInput && existingData && existingData[`r_${month}_fisik`]) {
            fisikInput.value = existingData[`r_${month}_fisik`];
        }
    });
    
    calculateTotal();
    calculateFisik();
}

function closeRealisasiModal() {
    document.getElementById('realisasiModal').classList.add('hidden');
    // Reset all inputs
    const allInputs = document.querySelectorAll('#realisasiModal input[type="text"], #realisasiModal input[type="number"]');
    allInputs.forEach(input => {
        input.value = '';
        if (input.hasAttribute('data-raw-value')) {
            input.setAttribute('data-raw-value', '');
        }
    });
    // Reset totals
    document.getElementById('totalKeuangan').textContent = 'Rp 0';
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
    
    document.getElementById('totalKeuangan').textContent = 'Rp ' + totalKeuangan.toLocaleString('id-ID');
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

function saveRealisasi() {
    const uraianId = document.getElementById('realisasiUraianId').value;
    
    if (!uraianId) {
        alert('ID Uraian tidak ditemukan');
        return;
    }
    
    // Collect all form data
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    
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
    const saveButton = document.querySelector('button[onclick="saveRealisasi()"]');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    
    fetch(`/pptk/uraian/${uraianId}/realisasi`, {
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
            alert(data.message);
            closeRealisasiModal();
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

<!-- Realisasi Target Modal -->
<div id="realisasiTargetModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeRealisasiTargetModal()"></div>
    
    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="card-gradient relative transform overflow-hidden rounded-2xl border border-slate-700/50 text-left shadow-2xl transition-all w-full max-w-3xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="border-b border-slate-700/50 px-6 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white" id="realisasiTargetTitle">Input Realisasi Target</h3>
                        <p class="text-sm text-slate-400 mt-1" id="realisasiTargetName">Nama Target</p>
                    </div>
                    <button type="button" onclick="closeRealisasiTargetModal()" 
                        class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="px-6 py-6 overflow-y-auto flex-1">
                <input type="hidden" id="realisasiTargetId">
                <input type="hidden" id="realisasiTargetSatuan">
                
                <!-- Table Header -->
                <div class="grid grid-cols-3 gap-4 mb-4 px-2">
                    <div class="text-sm font-semibold text-slate-400">Bulan</div>
                    <div class="text-sm font-semibold text-slate-400 text-center">Target</div>
                    <div class="text-sm font-semibold text-slate-400 text-center">Realisasi</div>
                </div>
                
                <!-- Month Rows -->
                <div class="space-y-3" id="realisasiTargetMonths">
                    <!-- Januari -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Januari</div>
                        <input type="text" name="target_januari" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_januari" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Februari -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Februari</div>
                        <input type="text" name="target_februari" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_februari" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Maret -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Maret</div>
                        <input type="text" name="target_maret" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_maret" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- April -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">April</div>
                        <input type="text" name="target_april" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_april" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Mei -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Mei</div>
                        <input type="text" name="target_mei" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_mei" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Juni -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Juni</div>
                        <input type="text" name="target_juni" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_juni" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Juli -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Juli</div>
                        <input type="text" name="target_juli" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_juli" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Agustus -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Agustus</div>
                        <input type="text" name="target_agustus" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_agustus" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- September -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">September</div>
                        <input type="text" name="target_september" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_september" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Oktober -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Oktober</div>
                        <input type="text" name="target_oktober" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_oktober" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- November -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">November</div>
                        <input type="text" name="target_november" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_november" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                    
                    <!-- Desember -->
                    <div class="grid grid-cols-3 gap-4 items-center">
                        <div class="text-sm text-white font-medium">Desember</div>
                        <input type="text" name="target_desember" placeholder="0" readonly
                            class="w-full bg-slate-800 text-slate-400 text-sm rounded-lg border border-slate-600 px-4 py-2 text-center" data-raw-value="">
                        <input type="number" name="realisasi_desember" placeholder="0" min="0" step="0.01" oninput="calculateRealisasiTargetTotal()"
                            class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500 placeholder-slate-500 text-center">
                    </div>
                </div>
                
                <!-- Total Summary -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-blue-400">Total Target</span>
                            <span class="text-lg font-bold text-white" id="totalTarget">0</span>
                        </div>
                    </div>
                    <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-green-400">Total Realisasi</span>
                            <span class="text-lg font-bold text-white" id="totalRealisasiTarget">0</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="border-t border-slate-700/50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse flex-shrink-0">
                <button type="button" onclick="closeRealisasiTargetModal()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="saveRealisasiTarget()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/20">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Realisasi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openRealisasiTargetModal(targetId, targetName, satuan, existingData) {
    document.getElementById('realisasiTargetId').value = targetId;
    document.getElementById('realisasiTargetName').textContent = targetName;
    document.getElementById('realisasiTargetSatuan').value = satuan ? satuan.toString() : '';
    document.getElementById('realisasiTargetModal').classList.remove('hidden');
    
    // Load existing data into inputs
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    
    months.forEach(month => {
        const targetInput = document.querySelector(`#realisasiTargetModal input[name="target_${month}"]`);
        const realisasiInput = document.querySelector(`#realisasiTargetModal input[name="realisasi_${month}"]`);
        
        // Load target data (readonly)
        if (targetInput && existingData && existingData[`target_${month}`]) {
            const targetValue = existingData[`target_${month}`];
            targetInput.setAttribute('data-raw-value', targetValue);
            targetInput.value = parseFloat(targetValue).toFixed(2);
        } else {
            targetInput.value = '0';
        }
        
        // Load existing realisasi data
        if (realisasiInput && existingData && existingData[`realisasi_${month}`]) {
            realisasiInput.value = existingData[`realisasi_${month}`];
        } else {
            realisasiInput.value = '';
        }
    });
    
    calculateRealisasiTargetTotal();
}

function closeRealisasiTargetModal() {
    document.getElementById('realisasiTargetModal').classList.add('hidden');
    // Reset all inputs
    const allInputs = document.querySelectorAll('#realisasiTargetModal input[type="number"]');
    allInputs.forEach(input => {
        input.value = '';
    });
    // Reset total
    document.getElementById('totalRealisasiTarget').textContent = '0';
}

function calculateRealisasiTargetTotal() {
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    let totalRealisasi = 0;
    let totalTarget = 0;
    
    months.forEach(month => {
        const realisasiInput = document.querySelector(`#realisasiTargetModal input[name="realisasi_${month}"]`);
        const targetInput = document.querySelector(`#realisasiTargetModal input[name="target_${month}"]`);
        
        // Calculate total realisasi
        if (realisasiInput && realisasiInput.value) {
            totalRealisasi += parseFloat(realisasiInput.value) || 0;
        }
        
        // Calculate total target
        if (targetInput && targetInput.getAttribute('data-raw-value')) {
            totalTarget += parseFloat(targetInput.getAttribute('data-raw-value')) || 0;
        }
    });
    
    const satuan = document.getElementById('realisasiTargetSatuan').value;
    document.getElementById('totalTarget').textContent = totalTarget.toFixed(2) + ' ' + satuan;
    document.getElementById('totalRealisasiTarget').textContent = totalRealisasi.toFixed(2) + ' ' + satuan;
}

function saveRealisasiTarget() {
    const targetId = document.getElementById('realisasiTargetId').value;
    
    if (!targetId) {
        alert('ID Target tidak ditemukan');
        return;
    }
    
    // Collect all form data
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    
    const formData = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    months.forEach(month => {
        const input = document.querySelector(`#realisasiTargetModal input[name="realisasi_${month}"]`);
        formData[`realisasi_${month}`] = input && input.value 
            ? parseFloat(input.value) || 0 
            : 0;
    });
    
    // Send AJAX request
    const saveButton = document.querySelector('button[onclick="saveRealisasiTarget()"]');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    
    fetch(`/pptk/target/${targetId}/realisasi`, {
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
            alert(data.message);
            closeRealisasiTargetModal();
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
