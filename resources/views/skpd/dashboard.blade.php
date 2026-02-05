@extends('layouts.app')

@section('title', 'Dashboard SKPD - KENANGAN')

@section('header-title', 'Dashboard SKPD')

@section('content')
<!-- Welcome Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white mb-1">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="text-slate-400">Berikut adalah ringkasan aktivitas SKPD Anda.</p>
</div>

<!-- Year Filter -->
<div class="card-gradient rounded-xl p-4 border border-slate-700/50 mb-6">
    <form action="{{ route('skpd.dashboard') }}" method="GET" id="yearFilterForm">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white"></i>
                </div>
                <div>
                    <label for="year" class="text-sm text-slate-400">Filter Tahun</label>
                    <select name="year" id="year"
                        class="block w-full sm:w-48 mt-1 px-3 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        onchange="document.getElementById('yearFilterForm').submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear==$year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if($selectedYear)
            <div class="flex items-center text-sm text-slate-400">
                <i class="fas fa-filter mr-2 text-purple-400"></i>
                Menampilkan data tahun <span class="text-white font-semibold mx-1">{{ $selectedYear }}</span>
            </div>
            @endif
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <!-- Total Program -->
    <div class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Total Program</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $totalPrograms }}
                </p>
                <p class="text-purple-400 text-sm mt-2">
                    <i class="fas fa-folder mr-1"></i>Program SKPD
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                <i class="fas fa-folder-open text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Total Kegiatan -->
    <div class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Total Kegiatan</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $totalKegiatan }}
                </p>
                <p class="text-blue-400 text-sm mt-2">
                    <i class="fas fa-tasks mr-1"></i>Kegiatan aktif
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                <i class="fas fa-list text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Total Subkegiatan -->
    <div class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Total Subkegiatan</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $totalSubkegiatan }}
                </p>
                <p class="text-amber-400 text-sm mt-2">
                    <i class="fas fa-sitemap mr-1"></i>Sub-kegiatan
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                <i class="fas fa-project-diagram text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Total Uraian -->
    <div class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Total Uraian</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $totalUraian }}
                </p>
                <p class="text-green-400 text-sm mt-2">
                    <i class="fas fa-file-alt mr-1"></i>Uraian detail
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                <i class="fas fa-align-left text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>


<!-- Progress Overview -->
<div class="card-gradient rounded-2xl p-6 border border-slate-700/50">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-white">Progres Anggaran</h2>
        <span class="text-sm text-slate-400">Tahun Anggaran 2026</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Dialaharkan -->
        <div>
            <div class="flex justify-between mb-2">
                <span class="text-sm text-slate-300">Dianggarkan</span>
                <span class="text-sm font-semibold text-white">Rp 0</span>
            </div>
            <div class="w-full bg-slate-700/50 rounded-full h-3">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-3 rounded-full" style="width: 32%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-1">32% dari total anggaran</p>
        </div>

        <!-- Terserap -->
        <div>
            <div class="flex justify-between mb-2">
                <span class="text-sm text-slate-300">Terserap</span>
                <span class="text-sm font-semibold text-white">Rp 0</span>
            </div>
            <div class="w-full bg-slate-700/50 rounded-full h-3">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full" style="width: 48%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-1">48% dari total anggaran</p>
        </div>

        <!-- Sisa -->
        <div>
            <div class="flex justify-between mb-2">
                <span class="text-sm text-slate-300">Sisa</span>
                <span class="text-sm font-semibold text-white">Rp 0</span>
            </div>
            <div class="w-full bg-slate-700/50 rounded-full h-3">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full" style="width: 20%"></div>
            </div>
            <p class="text-xs text-slate-400 mt-1">20% dari total anggaran</p>
        </div>
    </div>
</div>
@endsection