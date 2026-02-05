@extends('layouts.app')

@section('title', 'Tambah Bidang - KENANGAN')

@section('header-title', 'Tambah Bidang')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <a href="{{ route('skpd.bidang.index') }}" class="inline-flex items-center text-slate-400 hover:text-white transition-colors mb-4">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Data Bidang
    </a>
    <h1 class="text-2xl font-bold text-white">Tambah Bidang Baru</h1>
    <p class="text-slate-400 text-sm mt-1">Isi formulir di bawah untuk menambahkan bidang baru untuk {{ $skpd->nama }}</p>
</div>

<!-- Create Form -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-2xl">
    <form action="{{ route('skpd.bidang.store') }}" method="POST">
        @csrf

        <!-- Validation Errors -->
        @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/30">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                <div>
                    <p class="text-red-400 font-medium mb-2">Terjadi kesalahan:</p>
                    <ul class="text-sm text-red-400 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <!-- SKPD Info -->
        <div class="mb-6 p-4 rounded-lg bg-slate-800/50 border border-slate-700/50">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mr-3">
                    <i class="fas fa-building text-xs text-white"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500">SKPD</p>
                    <p class="text-sm text-white font-medium">{{ $skpd->nama }}</p>
                </div>
            </div>
        </div>

        <!-- Nama Bidang -->
        <div class="mb-6">
            <label for="nama" class="block text-sm font-medium text-slate-300 mb-2">
                Nama Bidang <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <i class="fas fa-sitemap"></i>
                </span>
                <input type="text"
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-colors"
                    placeholder="Contoh: Bidang Kesehatan Masyarakat"
                    required>
            </div>
            <p class="mt-1.5 text-xs text-slate-500">Nama bidang yang akan ditambahkan</p>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
            <a href="{{ route('skpd.bidang.index') }}"
                class="inline-flex items-center px-4 py-2.5 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-500/25">
                <i class="fas fa-save mr-2"></i>
                Simpan
            </button>
        </div>
    </form>
</div>

<!-- Info Box -->
<div class="mt-6 p-4 rounded-lg bg-blue-500/10 border border-blue-500/30">
    <div class="flex items-start">
        <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0 mr-3">
            <i class="fas fa-info-circle text-blue-400"></i>
        </div>
        <div>
            <p class="text-sm text-blue-400 font-medium">Informasi</p>
            <p class="text-sm text-slate-400 mt-1">
                Bidang akan terhubung secara otomatis dengan SKPD Anda. Pastikan nama bidang ditulis dengan jelas dan sesuai.
            </p>
        </div>
    </div>
</div>
@endsection