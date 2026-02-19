@extends('layouts.app')

@section('title', 'Target - KENANGAN')

@section('header-title', 'Target Uraian')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Target Uraian</h1>
        <p class="text-slate-400 text-sm mt-1">Daftar target untuk subkegiatan: {{ $subkegiatan->nama }}</p>
    </div>
    <a href="{{ route('pptk.subkegiatan.index') }}"
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
                <div
                    class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3">
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
        <button type="button" onclick="openAddTargetModal({{ $uraianItem->id }}, '{{ $uraianItem->nama }}')"
            class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white text-sm font-medium rounded-lg hover:from-amber-600 hover:to-orange-700 transition-all shadow-lg shadow-amber-500/20">
            <i class="fas fa-plus mr-2"></i>
            Tambah Target
        </button>
    </div>

    <!-- Targets List -->
    @if($uraianItem->targets->count() > 0)
    <div class="ml-11 overflow-x-auto">
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
                        <span
                            class="inline-flex items-center px-2 py-1 bg-blue-500/10 border border-blue-500/30 rounded text-blue-400 text-sm font-medium">
                            {{ $target->target_januari + $target->target_februari + $target->target_maret +
                            $target->target_april + $target->target_mei + $target->target_juni + $target->target_juli +
                            $target->target_agustus + $target->target_september + $target->target_oktober +
                            $target->target_november + $target->target_desember }} {{ $target->satuan }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="flex items-center justify-center space-x-2">
                            <button type="button"
                                onclick="openEditTargetModal({{ $target->id }}, {{ $uraianItem->id }}, '{{ $uraianItem->nama }}')"
                                class="w-8 h-8 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center hover:bg-blue-500/20 transition-colors"
                                title="Edit Target">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button type="button"
                                onclick="openRealisasiTargetModal({{ $target->id }}, '{{ $target->keterangan }}')"
                                class="w-8 h-8 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 flex items-center justify-center hover:bg-green-500/20 transition-colors"
                                title="Input Realisasi Target">
                                <i class="fas fa-chart-line text-sm"></i>
                            </button>
                            <button type="button" onclick="deleteTarget({{ $target->id }})"
                                class="w-8 h-8 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 flex items-center justify-center hover:bg-red-500/20 transition-colors"
                                title="Hapus Target">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="ml-11 text-center py-8 border-2 border-dashed border-slate-700/50 rounded-lg">
        <div class="w-12 h-12 rounded-full bg-slate-700/50 flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-inbox text-xl text-slate-500"></i>
        </div>
        <p class="text-slate-400 text-sm">Belum ada target untuk uraian ini</p>
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
        <a href="{{ route('pptk.uraian.create', ['subkegiatan_id' => $subkegiatan->id]) }}"
            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all">
            <i class="fas fa-plus mr-2"></i>
            Tambah Uraian
        </a>
    </div>
</div>
@endif

<!-- Add Target Modal -->
<div id="addTargetModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="closeAddTargetModal()"></div>

    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div
            class="card-gradient relative transform overflow-hidden rounded-2xl border border-slate-700/50 text-left shadow-2xl transition-all w-full max-w-3xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="border-b border-slate-700/50 px-6 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-white" id="modal-title">Tambah Target</h3>
                        <p class="text-sm text-slate-400 mt-1" id="modal-uraian-name">Nama Uraian</p>
                    </div>
                    <button type="button" onclick="closeAddTargetModal()"
                        class="text-slate-400 hover:text-white transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6 overflow-y-auto flex-1">
                <form id="addTargetForm">
                    <input type="hidden" id="targetUraianId" name="uraian_id">
                    <input type="hidden" id="editTargetId" name="edit_target_id" value="">

                    <!-- Target Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Keterangan *</label>
                            <input type="text" name="keterangan" required
                                class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500"
                                placeholder="Contoh: Pembelian barang">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Spesifikasi *</label>
                            <input type="text" name="spesifikasi" required
                                class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500"
                                placeholder="Contoh: Barang jadi, kualitas A">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Jumlah *</label>
                            <input type="number" name="jumlah" required min="1" step="0.01"
                                class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500"
                                placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-400 mb-2">Satuan *</label>
                            <input type="text" name="satuan" required
                                class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500"
                                placeholder="Contoh: unit, pcs, kg">
                        </div>
                    </div>

                    <!-- Monthly Targets -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-slate-400 mb-3">Target Output per Bulan</h4>
                        <div class="grid grid-cols-3 gap-4 items-center mb-2 px-2">
                            <div class="text-sm font-semibold text-slate-400">Bulan</div>
                            <div class="text-sm font-semibold text-slate-400 text-center">Target Output</div>
                            <div class="text-sm font-semibold text-slate-400 text-center">Kumulatif</div>
                        </div>

                        <div class="space-y-2" id="monthlyTargets">
                            <!-- Januari -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Januari</div>
                                <input type="number" name="target_januari" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_januari">0</div>
                            </div>

                            <!-- Februari -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Februari</div>
                                <input type="number" name="target_februari" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_februari">0</div>
                            </div>

                            <!-- Maret -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Maret</div>
                                <input type="number" name="target_maret" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_maret">0</div>
                            </div>

                            <!-- April -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">April</div>
                                <input type="number" name="target_april" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_april">0</div>
                            </div>

                            <!-- Mei -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Mei</div>
                                <input type="number" name="target_mei" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_mei">0</div>
                            </div>

                            <!-- Juni -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Juni</div>
                                <input type="number" name="target_juni" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_juni">0</div>
                            </div>

                            <!-- Juli -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Juli</div>
                                <input type="number" name="target_juli" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_juli">0</div>
                            </div>

                            <!-- Agustus -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Agustus</div>
                                <input type="number" name="target_agustus" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_agustus">0</div>
                            </div>

                            <!-- September -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">September</div>
                                <input type="number" name="target_september" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_september">0</div>
                            </div>

                            <!-- Oktober -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Oktober</div>
                                <input type="number" name="target_oktober" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_oktober">0</div>
                            </div>

                            <!-- November -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">November</div>
                                <input type="number" name="target_november" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_november">0</div>
                            </div>

                            <!-- Desember -->
                            <div class="grid grid-cols-3 gap-4 items-center">
                                <div class="text-sm text-white font-medium">Desember</div>
                                <input type="number" name="target_desember" placeholder="0" min="0" step="0.01"
                                    oninput="calculateKumulatif()"
                                    class="w-full bg-slate-700 text-white text-sm rounded-lg border border-slate-600 px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 placeholder-slate-500 text-center">
                                <div class="text-sm text-slate-400 text-center" id="kumulatif_desember">0</div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div class="mt-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-amber-400">Total Target Tahunan</span>
                            <span class="text-lg font-bold text-white" id="totalTargetTahunan">0</span>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div
                class="border-t border-slate-700/50 px-6 py-4 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse flex-shrink-0">
                <button type="button" onclick="closeAddTargetModal()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-slate-700 text-white text-sm font-medium rounded-lg hover:bg-slate-600 transition-colors">
                    Batal
                </button>
                <button type="button" onclick="saveTarget()"
                    class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white text-sm font-medium rounded-lg hover:from-amber-600 hover:to-orange-700 transition-all shadow-lg shadow-amber-500/20">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Target
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openAddTargetModal(uraianId, uraianName) {
    document.getElementById('targetUraianId').value = uraianId;
    document.getElementById('modal-uraian-name').textContent = uraianName;
    document.getElementById('editTargetId').value = '';
    document.getElementById('modal-title').textContent = 'Tambah Target';
    document.getElementById('addTargetModal').classList.remove('hidden');
    
    // Reset form
    document.getElementById('addTargetForm').reset();
    calculateKumulatif();
    
    // Reset save button
    const saveButton = document.querySelector('button[onclick="saveTarget()"]');
    saveButton.innerHTML = '<i class="fas fa-save mr-2"></i> Simpan Target';
    saveButton.onclick = saveTarget;
}

function openEditTargetModal(targetId, uraianId, uraianName) {
    document.getElementById('targetUraianId').value = uraianId;
    document.getElementById('modal-uraian-name').textContent = uraianName;
    document.getElementById('editTargetId').value = targetId;
    document.getElementById('modal-title').textContent = 'Edit Target';
    document.getElementById('addTargetModal').classList.remove('hidden');
    
    // Fetch target data
    fetch(`/pptk/target/${targetId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const target = data.target;
            document.querySelector('input[name="keterangan"]').value = target.keterangan;
            document.querySelector('input[name="spesifikasi"]').value = target.spesifikasi;
            document.querySelector('input[name="jumlah"]').value = target.jumlah;
            document.querySelector('input[name="satuan"]').value = target.satuan;
            
            // Set monthly targets
            const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
            months.forEach(month => {
                const input = document.querySelector(`input[name="target_${month}"]`);
                if (input) {
                    input.value = target[`target_${month}`] || 0;
                }
            });
            
            calculateKumulatif();
            
            // Update save button
            const saveButton = document.querySelector('button[onclick="saveTarget()"]');
            saveButton.innerHTML = '<i class="fas fa-save mr-2"></i> Update Target';
            saveButton.onclick = updateTarget;
        } else {
            alert('Gagal memuat data target');
            closeAddTargetModal();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data target');
        closeAddTargetModal();
    });
}

function closeAddTargetModal() {
    document.getElementById('addTargetModal').classList.add('hidden');
    document.getElementById('addTargetForm').reset();
    document.getElementById('editTargetId').value = '';
    calculateKumulatif();
}

function calculateKumulatif() {
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    let total = 0;
    
    months.forEach((month, index) => {
        const input = document.querySelector(`input[name="target_${month}"]`);
        const kumulatif = document.getElementById(`kumulatif_${month}`);
        
        if (input && kumulatif) {
            const value = parseFloat(input.value) || 0;
            total += value;
            kumulatif.textContent = total.toFixed(2);
        }
    });
    
    document.getElementById('totalTargetTahunan').textContent = total.toFixed(2);
}

function saveTarget() {
    const uraianId = document.getElementById('targetUraianId').value;
    const form = document.getElementById('addTargetForm');
    
    if (!uraianId) {
        alert('ID Uraian tidak ditemukan');
        return;
    }
    
    // Collect form data
    const formData = new FormData(form);
    const data = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        uraian_id: uraianId
    };
    
    formData.forEach((value, key) => {
        data[key] = value;
    });
    
    // Validation: Check if total equals jumlah
    const jumlah = parseFloat(formData.get('jumlah')) || 0;
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    let totalTarget = 0;
    
    months.forEach(month => {
        totalTarget += parseFloat(formData.get(`target_${month}`)) || 0;
    });
    
    if (Math.abs(totalTarget - jumlah) > 0.01) {
        alert(`Total target tahunan (${totalTarget.toFixed(2)}) harus sama dengan jumlah (${jumlah}).`);
        return;
    }
    
    // Send AJAX request
    const saveButton = document.querySelector('button[onclick="saveTarget()"]');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
    
    fetch(`/pptk/uraian/${uraianId}/target`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            //alert(data.message);
            closeAddTargetModal();
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

function updateTarget() {
    const targetId = document.getElementById('editTargetId').value;
    const form = document.getElementById('addTargetForm');
    
    if (!targetId) {
        alert('ID Target tidak ditemukan');
        return;
    }
    
    // Collect form data
    const formData = new FormData(form);
    const data = {
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        _method: 'PUT'
    };
    
    formData.forEach((value, key) => {
        if (key !== 'edit_target_id') {
            data[key] = value;
        }
    });
    
    // Validation: Check if total equals jumlah
    const jumlah = parseFloat(formData.get('jumlah')) || 0;
    const months = ['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'];
    let totalTarget = 0;
    
    months.forEach(month => {
        totalTarget += parseFloat(formData.get(`target_${month}`)) || 0;
    });
    
    if (Math.abs(totalTarget - jumlah) > 0.01) {
        alert(`Total target tahunan (${totalTarget.toFixed(2)}) harus sama dengan jumlah (${jumlah}).`);
        return;
    }
    
    // Send AJAX request
    const saveButton = document.querySelector('button[onclick="saveTarget()"]');
    const originalText = saveButton.innerHTML;
    saveButton.disabled = true;
    saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengupdate...';
    
    fetch(`/pptk/target/${targetId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddTargetModal();
            window.location.reload();
        } else {
            alert('Terjadi kesalahan saat mengupdate data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data. Silakan coba lagi.');
    })
    .finally(() => {
        saveButton.disabled = false;
        saveButton.innerHTML = originalText;
    });
}

function deleteTarget(targetId) {
    if (!confirm('Apakah Anda yakin ingin menghapus target ini?')) {
        return;
    }
    
    fetch(`/pptk/target/${targetId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert('Terjadi kesalahan saat menghapus data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus data. Silakan coba lagi.');
    });
}
</script>
@endsection