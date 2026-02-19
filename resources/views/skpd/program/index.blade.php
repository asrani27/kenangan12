@extends('layouts.app')

@section('title', 'Kelola Data Rekening Belanja - KENANGAN')

@section('header-title', 'Kelola Data Rekening Belanja')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Data Rekening Belanja</h1>
        <p class="text-slate-400 text-sm mt-1">Kelola data rekening belanja untuk {{ $skpd->nama }}</p>
    </div>
    <a href="{{ route('skpd.program.create') }}"
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-500/25">
        <i class="fas fa-plus mr-2"></i>
        Tambah Program
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

<!-- Year Filter -->
<div class="card-gradient rounded-xl p-4 border border-slate-700/50 mb-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center space-x-3">
            <label for="yearFilter" class="text-sm font-medium text-slate-300">Filter Tahun:</label>
            <select id="yearFilter"
                class="bg-slate-700/50 border border-slate-600 text-white text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-2">

                @foreach($years as $year)
                <option value="{{ $year }}" {{ $selectedYear==$year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<!-- Program Table -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-slate-700/50">
                    <th class="pb-3 text-sm font-semibold text-slate-400 w-20">No</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400">Kode Program</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400">Nama Program</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 hidden md:table-cell">PPTK</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @if($showSubunitGrouping ?? false)
                <!-- Display with kode_subunit grouping -->
                @if($kelurahans->count() > 0)
                @foreach($kelurahans as $kelurahanIndex => $kelurahan)
                @php
                $programsInSubunit = $programs->where('kode_subunit', $kelurahan->kode_subunit);
                @endphp

                @if($programsInSubunit->count() > 0)
                <!-- Kelurahan Header Row -->
                <tr class="bg-gradient-to-r from-indigo-900/50 to-purple-900/50 border-l-4 border-indigo-500">

                    <td class="py-4 px-4" colspan="2">
                        <div class="flex">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-sm text-white"></i>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white font-mono">{{ str_replace("\r", '',
                                    $kelurahan->nama) }}</div>
                                <div class="text-xs text-slate-300">{{ $kelurahan->kode_subunit}}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-500/20 text-indigo-400">
                            {{ $programsInSubunit->count() }} Program
                        </span>
                    </td>
                    <td class="py-4 px-4 text-sm text-slate-300 hidden md:table-cell"></td>
                    <td class="py-4 px-4"></td>
                </tr>

                @php $programIndex = 0; @endphp
                @foreach($programsInSubunit as $program)
                @php $programIndex++; @endphp

                <tr class="program-row">
                    <td class="py-3 text-sm text-slate-300 pl-8">{{ $programIndex }}</td>
                    <td class="py-3 text-sm text-slate-300 font-mono">{{ $program->kode }}</td>
                    <td class="py-3">
                        <div class="flex items-center">
                            <div
                                class="w-7 h-7 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mr-2">
                                <i class="fas fa-list-alt text-xs text-white"></i>
                            </div>
                            <span class="text-sm text-white font-medium">{{ $program->nama }}</span>
                        </div>
                    </td>
                    <td class="py-3 text-sm text-slate-300 hidden md:table-cell"></td>
                    <td class="py-3">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('skpd.kegiatan.create', $program->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition-colors"
                                title="Tambah Kegiatan">
                                <i class="fas fa-plus text-sm"></i>
                            </a>
                            <a href="{{ route('skpd.program.edit', $program->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button
                                onclick="confirmDelete({{ $program->id }}, '{{ $program->kode }} - {{ $program->nama }}')"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                @if($program->kegiatan && $program->kegiatan->count() > 0)
                @foreach($program->kegiatan->where('kode_subunit', $kelurahan->kode_subunit) as $kegiatan)
                <tr class="bg-slate-800/30">
                    <td class="py-2 text-sm text-slate-400"></td>
                    <td class="py-2 text-sm text-slate-300 font-mono">{{ $kegiatan->kode }}</td>
                    <td class="py-2">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-md bg-indigo-500/20 flex items-center justify-center mr-2">
                                <i class="fas fa-tasks text-xs text-indigo-400"></i>
                            </div>
                            <span class="text-sm text-slate-300">{{ $kegiatan->nama }}</span>
                        </div>
                    </td>
                    <td class="py-2 text-sm text-slate-400 hidden md:table-cell"></td>
                    <td class="py-2">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('skpd.subkegiatan.create', $kegiatan->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition-colors"
                                title="Tambah Sub-kegiatan">
                                <i class="fas fa-plus text-xs"></i>
                            </a>
                            <a href="{{ route('skpd.kegiatan.edit', $kegiatan->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit Kegiatan">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <button
                                onclick="confirmDeleteKegiatan({{ $kegiatan->id }}, '{{ $kegiatan->kode }} - {{ $kegiatan->nama }}')"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus Kegiatan">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            @if($kegiatan->sub_kegiatan && $kegiatan->sub_kegiatan->count() > 0)
                            <span class="text-xs text-slate-400"
                                title="{{ $kegiatan->sub_kegiatan->count() }} Sub Kegiatan">
                                <i class="fas fa-layer-group mr-1"></i>{{ $kegiatan->sub_kegiatan->count() }}
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>

                @if($kegiatan->sub_kegiatan && $kegiatan->sub_kegiatan->count() > 0)
                @foreach($kegiatan->sub_kegiatan->where('kode_subunit', $kelurahan->kode_subunit) as $subKegiatan)
                <tr class="bg-slate-700/20">
                    <td class="py-2 text-sm text-slate-400"></td>
                    <td class="py-2 text-sm text-slate-300 font-mono">{{ $subKegiatan->kode }}</td>
                    <td class="py-2 pl-8">
                        <div class="flex items-center">
                            <span class="text-sm text-slate-300">{{ $subKegiatan->nama }}</span>
                        </div>
                    </td>
                    <td class="py-2 hidden md:table-cell">
                        <select
                            class="pptk-selector bg-slate-600/50 border border-slate-500 text-white text-xs rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-1.5"
                            data-subkegiatan-id="{{ $subKegiatan->id }}"
                            data-url="{{ route('skpd.subkegiatan.updatePptk') }}">
                            <option value="">Pilih PPTK</option>
                            @foreach($pptks as $pptk)
                            <option value="{{ $pptk->nip_pptk }}" {{ $subKegiatan->nip_pptk == $pptk->nip_pptk ?
                                'selected' : '' }}>
                                {{ $pptk->nama_pptk }} ({{ $pptk->nip_pptk }})
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-2">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('skpd.subkegiatan.edit', $subKegiatan->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit Sub-kegiatan">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <button
                                onclick="confirmDeleteSubkegiatan({{ $subKegiatan->id }}, '{{ $subKegiatan->kode }} - {{ $subKegiatan->nama }}')"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus Sub-kegiatan">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
                @endforeach
                @endif
                @endforeach
                @endif
                @endforeach
                @else
                <!-- No kelurahans found -->
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-slate-700/50 flex items-center justify-center mb-4">
                                <i class="fas fa-inbox text-2xl text-slate-500"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Belum ada data kelurahan</p>
                        </div>
                    </td>
                </tr>
                @endif
                @else
                <!-- Regular display for non-special SKPD codes -->

                @forelse($programs->where('kode_subunit', $skpd->kode_subunit) as $index => $program)
                <tr class="program-row">
                    <td class="py-3 text-sm text-slate-300">{{ $index + 1 }}</td>
                    <td class="py-3 text-sm text-slate-300 font-mono">{{ $program->kode }}</td>
                    <td class="py-3">
                        <div class="flex items-center">
                            <div
                                class="w-7 h-7 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mr-2">
                                <i class="fas fa-list-alt text-xs text-white"></i>
                            </div>
                            <span class="text-sm text-white font-medium">{{ $program->nama }}</span>
                        </div>
                    </td>
                    <td class="py-3 text-sm text-slate-300 hidden md:table-cell"></td>
                    <td class="py-3">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('skpd.kegiatan.create', $program->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition-colors"
                                title="Tambah Kegiatan">
                                <i class="fas fa-plus text-sm"></i>
                            </a>
                            <a href="{{ route('skpd.program.edit', $program->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button
                                onclick="confirmDelete({{ $program->id }}, '{{ $program->kode }} - {{ $program->nama }}')"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                @if($program->kegiatan && $program->kegiatan->count() > 0)
                @foreach($program->kegiatan->where('kode_subunit', $skpd->kode_subunit) as $kegiatan)
                <tr class="bg-slate-800/30">
                    <td class="py-2 text-sm text-slate-400"></td>
                    <td class="py-2 text-sm text-slate-300 font-mono">{{ $kegiatan->kode }}</td>
                    <td class="py-2">
                        <div class="flex items-center">
                            <div class="w-6 h-6 rounded-md bg-indigo-500/20 flex items-center justify-center mr-2">
                                <i class="fas fa-tasks text-xs text-indigo-400"></i>
                            </div>
                            <span class="text-sm text-slate-300">{{ $kegiatan->nama }}</span>
                        </div>
                    </td>
                    <td class="py-2 text-sm text-slate-400 hidden md:table-cell"></td>
                    <td class="py-2">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('skpd.subkegiatan.create', $kegiatan->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition-colors"
                                title="Tambah Sub-kegiatan">
                                <i class="fas fa-plus text-xs"></i>
                            </a>
                            <a href="{{ route('skpd.kegiatan.edit', $kegiatan->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit Kegiatan">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <button
                                onclick="confirmDeleteKegiatan({{ $kegiatan->id }}, '{{ $kegiatan->kode }} - {{ $kegiatan->nama }}')"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus Kegiatan">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                            @if($kegiatan->sub_kegiatan && $kegiatan->sub_kegiatan->count() > 0)
                            <span class="text-xs text-slate-400"
                                title="{{ $kegiatan->sub_kegiatan->count() }} Sub Kegiatan">
                                <i class="fas fa-layer-group mr-1"></i>{{ $kegiatan->sub_kegiatan->count() }}
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>

                @if($kegiatan->sub_kegiatan && $kegiatan->sub_kegiatan->count() > 0)
                @foreach($kegiatan->sub_kegiatan->where('kode_subunit', $skpd->kode_subunit) as $subKegiatan)
                <tr class="bg-slate-700/20">
                    <td class="py-2 text-sm text-slate-400"></td>
                    <td class="py-2 text-sm text-slate-300 font-mono">{{ $subKegiatan->kode }}</td>
                    <td class="py-2 pl-8">
                        <div class="flex items-center">
                            <span class="text-sm text-slate-300">{{ $subKegiatan->nama }}</span>
                        </div>
                    </td>
                    <td class="py-2 hidden md:table-cell">
                        <select
                            class="pptk-selector bg-slate-600/50 border border-slate-500 text-white text-xs rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-1.5"
                            data-subkegiatan-id="{{ $subKegiatan->id }}"
                            data-url="{{ route('skpd.subkegiatan.updatePptk') }}">
                            <option value="">Pilih PPTK</option>
                            @foreach($pptks as $pptk)
                            <option value="{{ $pptk->nip_pptk }}" {{ $subKegiatan->nip_pptk == $pptk->nip_pptk ?
                                'selected' : '' }}>
                                {{ $pptk->nama_pptk }} ({{ $pptk->nip_pptk }})
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="py-2">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('skpd.subkegiatan.edit', $subKegiatan->id) }}"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit Sub-kegiatan">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <button
                                onclick="confirmDeleteSubkegiatan({{ $subKegiatan->id }}, '{{ $subKegiatan->kode }} - {{ $subKegiatan->nama }}')"
                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus Sub-kegiatan">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
                @endforeach
                @endif
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-slate-700/50 flex items-center justify-center mb-4">
                                <i class="fas fa-inbox text-2xl text-slate-500"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Belum ada data rekening belanja</p>
                            <a href="{{ route('skpd.program.create') }}"
                                class="text-purple-400 text-sm hover:text-purple-300 mt-2">
                                Tambah program sekarang
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Program Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-md w-full transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hapus Program?</h3>
            <p class="text-slate-400 text-sm mb-6">
                Apakah Anda yakin ingin menghapus program "<span id="programName"
                    class="text-white font-medium"></span>"?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex items-center justify-center space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Kegiatan Confirmation Modal -->
<div id="deleteKegiatanModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-md w-full transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hapus Kegiatan?</h3>
            <p class="text-slate-400 text-sm mb-6">
                Apakah Anda yakin ingin menghapus kegiatan "<span id="kegiatanName"
                    class="text-white font-medium"></span>"?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <form id="deleteKegiatanForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex items-center justify-center space-x-3">
                    <button type="button" onclick="closeDeleteKegiatanModal()"
                        class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Sub-kegiatan Confirmation Modal -->
<div id="deleteSubkegiatanModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-md w-full transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hapus Sub-kegiatan?</h3>
            <p class="text-slate-400 text-sm mb-6">
                Apakah Anda yakin ingin menghapus sub-kegiatan "<span id="subkegiatanName"
                    class="text-white font-medium"></span>"?
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <form id="deleteSubkegiatanForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex items-center justify-center space-x-3">
                    <button type="button" onclick="closeDeleteSubkegiatanModal()"
                        class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        document.getElementById('programName').textContent = name;
        document.getElementById('deleteForm').action = '/skpd/program/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    function confirmDeleteKegiatan(id, name) {
        document.getElementById('kegiatanName').textContent = name;
        document.getElementById('deleteKegiatanForm').action = '/skpd/kegiatan/' + id;
        document.getElementById('deleteKegiatanModal').classList.remove('hidden');
        document.getElementById('deleteKegiatanModal').classList.add('flex');
    }

    function closeDeleteKegiatanModal() {
        document.getElementById('deleteKegiatanModal').classList.add('hidden');
        document.getElementById('deleteKegiatanModal').classList.remove('flex');
    }

    function confirmDeleteSubkegiatan(id, name) {
        document.getElementById('subkegiatanName').textContent = name;
        document.getElementById('deleteSubkegiatanForm').action = '/skpd/subkegiatan/' + id;
        document.getElementById('deleteSubkegiatanModal').classList.remove('hidden');
        document.getElementById('deleteSubkegiatanModal').classList.add('flex');
    }

    function closeDeleteSubkegiatanModal() {
        document.getElementById('deleteSubkegiatanModal').classList.add('hidden');
        document.getElementById('deleteSubkegiatanModal').classList.remove('flex');
    }

    // Year filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const yearFilter = document.getElementById('yearFilter');
        
        if (yearFilter) {
            yearFilter.addEventListener('change', function() {
                const selectedYear = this.value;
                const url = new URL(window.location);
                
                if (selectedYear) {
                    url.searchParams.set('year', selectedYear);
                } else {
                    url.searchParams.delete('year');
                }
                
                window.location.href = url.toString();
            });
        }

        // PPTK selector functionality
        const pptkSelectors = document.querySelectorAll('.pptk-selector');
        
        pptkSelectors.forEach(function(selector) {
            selector.addEventListener('change', function() {
                const subkegiatanId = this.getAttribute('data-subkegiatan-id');
                const pptkNip = this.value;
                const url = this.getAttribute('data-url');
                
                // Send AJAX request to update PPTK
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        subkegiatan_id: subkegiatanId,
                        nip_pptk: pptkNip
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showToast('PPTK berhasil diperbarui!', 'success');
                    } else {
                        // Show error message
                        showToast(data.message || 'Gagal memperbarui PPTK', 'error');
                        // Revert selection if failed
                        this.value = this.getAttribute('data-original-value');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat memperbarui PPTK', 'error');
                    // Revert selection if failed
                    this.value = this.getAttribute('data-original-value');
                });
            });
        });
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white text-sm shadow-lg z-50 transition-all transform ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                ${message}
            </div>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
</script>
@endsection