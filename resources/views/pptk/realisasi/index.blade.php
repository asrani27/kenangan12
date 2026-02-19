@extends('layouts.app')

@section('title', 'Realisasi - KENANGAN')

@section('header-title', 'Realisasi')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Realisasi</h1>
        <p class="text-slate-400 text-sm mt-1">Input realisasi keuangan dan fisik per subkegiatan</p>
    </div>

    <!-- Year Filter -->
    @if($years->count() > 0)
    <form method="GET" action="{{ route('pptk.realisasi.index') }}" class="mt-4 sm:mt-0">
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
                            <span class="text-sm text-white font-medium pl-8">{{ $subkegiatanItem->nama }}</span>
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
                            <a href="{{ route('pptk.realisasi.show', ['id' => $subkegiatanItem->id]) }}"
                                class="w-8 h-8 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white flex items-center justify-center hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-500/20"
                                title="Realisasi">
                                <i class="fas fa-chart-line text-sm"></i>
                            </a>
                        </div>
                    </td>
                </tr>
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
@endsection