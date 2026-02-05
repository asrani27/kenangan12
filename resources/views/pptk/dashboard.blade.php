@extends('layouts.app')

@section('title', 'Dashboard PPTK - KENANGAN')

@section('header-title', 'Dashboard PPTK')

@section('content')
<!-- Welcome Section -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white mb-1">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="text-slate-400">Berikut adalah ringkasan aktivitas PPTK Anda.</p>
</div>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
    <!-- Total Sub Kegiatan -->
    <div class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">Total Sub Kegiatan</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $totalSubkegiatan }}</p>
                <p class="text-blue-400 text-sm mt-2"><i class="fas fa-sitemap mr-1"></i>Sub-kegiatan PPTK</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                <i class="fas fa-list-alt text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Total Uraian -->
    <div class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">Total Uraian</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $totalUraian }}</p>
                <p class="text-yellow-400 text-sm mt-2"><i class="fas fa-file-alt mr-1"></i>Uraian detail</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-yellow-500 to-orange-600 flex items-center justify-center">
                <i class="fas fa-align-left text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Total DPA -->
    <div class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">Total DPA</p>
                <p class="text-3xl font-bold text-white mt-2">Rp {{ number_format($totalDPA, 0, ',', '.') }}</p>
                <p class="text-green-400 text-sm mt-2"><i class="fas fa-coins mr-1"></i>Anggaran DPA</p>
            </div>
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Realisasi Belanja -->
<div class="card-gradient rounded-2xl p-6 border border-slate-700/50">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-white">Realisasi Belanja</h2>
        <span class="text-sm text-slate-400">Bulan Februari 2026</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Belanja Barang -->
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-400 text-sm"></i>
                </div>
                <span class="text-sm text-slate-300">Belanja Barang</span>
            </div>
            <div class="flex justify-between items-baseline mb-2">
                <span class="text-lg font-bold text-white">Rp 0</span>
                <span class="text-xs text-green-400">75%</span>
            </div>
            <div class="w-full bg-slate-700/50 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <!-- Belanja Modal -->
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center">
                    <i class="fas fa-building text-purple-400 text-sm"></i>
                </div>
                <span class="text-sm text-slate-300">Belanja Modal</span>
            </div>
            <div class="flex justify-between items-baseline mb-2">
                <span class="text-lg font-bold text-white">Rp 0</span>
                <span class="text-xs text-yellow-400">50%</span>
            </div>
            <div class="w-full bg-slate-700/50 rounded-full h-2">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full" style="width: 50%"></div>
            </div>
        </div>

        <!-- Belanja Pegawai -->
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center">
                    <i class="fas fa-users text-green-400 text-sm"></i>
                </div>
                <span class="text-sm text-slate-300">Belanja Pegawai</span>
            </div>
            <div class="flex justify-between items-baseline mb-2">
                <span class="text-lg font-bold text-white">Rp 0</span>
                <span class="text-xs text-green-400">90%</span>
            </div>
            <div class="w-full bg-slate-700/50 rounded-full h-2">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" style="width: 90%"></div>
            </div>
        </div>

        <!-- Belanja Lainnya -->
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                    <i class="fas fa-ellipsis-h text-yellow-400 text-sm"></i>
                </div>
                <span class="text-sm text-slate-300">Belanja Lainnya</span>
            </div>
            <div class="flex justify-between items-baseline mb-2">
                <span class="text-lg font-bold text-white">Rp 0</span>
                <span class="text-xs text-blue-400">60%</span>
            </div>
            <div class="w-full bg-slate-700/50 rounded-full h-2">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 h-2 rounded-full" style="width: 60%"></div>
            </div>
        </div>
    </div>
</div>
@endsection