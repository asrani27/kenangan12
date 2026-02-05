@extends('layouts.app')

@section('title', 'Laporan RFK - KENANGAN')

@section('header-title', 'Laporan RFK')

@section('content')
<!-- Under Development Section -->
<div class="flex flex-col items-center justify-center min-h-[400px] card-gradient rounded-2xl p-8 border border-slate-700/50">
    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500/20 to-indigo-600/20 flex items-center justify-center mb-6 animate-pulse">
        <i class="fas fa-tools text-4xl text-purple-400"></i>
    </div>
    
    <h1 class="text-3xl font-bold text-white mb-3">Sedang dalam Pengembangan</h1>
    <p class="text-slate-400 text-center max-w-md mb-8">
        Halaman Laporan RFK (Rencana Fisik dan Keuangan) sedang dikembangkan. 
        Fitur ini akan segera tersedia untuk membantu Anda memantau progres fisik dan keuangan.
    </p>
    
    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4">
        <a href="{{ route('skpd.dashboard') }}" 
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-500/25">
            <i class="fas fa-home mr-2"></i>
            Kembali ke Dashboard
        </a>
        <a href="{{ route('skpd.program.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-colors">
            <i class="fas fa-list-alt mr-2"></i>
            Lihat Data Rekening Belanja
        </a>
    </div>
</div>

<!-- Feature Preview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500/20 to-cyan-600/20 flex items-center justify-center mb-4">
            <i class="fas fa-chart-bar text-2xl text-blue-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Monitoring Fisik</h3>
        <p class="text-sm text-slate-400">Pantau progres fisik dari setiap kegiatan dan sub-kegiatan secara real-time.</p>
    </div>
    
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-green-500/20 to-emerald-600/20 flex items-center justify-center mb-4">
            <i class="fas fa-wallet text-2xl text-green-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Monitoring Keuangan</h3>
        <p class="text-sm text-slate-400">Lihat penggunaan anggaran dan progres serapan dana secara detail.</p>
    </div>
    
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500/20 to-pink-600/20 flex items-center justify-center mb-4">
            <i class="fas fa-file-pdf text-2xl text-purple-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Export Laporan</h3>
        <p class="text-sm text-slate-400">Download laporan RFK dalam format PDF atau Excel untuk keperluan administrasi.</p>
    </div>
</div>
@endsection