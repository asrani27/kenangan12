@extends('layouts.app')

@section('title', 'Tambah Sub-kegiatan - KENANGAN')

@section('header-title', 'Tambah Sub-kegiatan')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Sub-kegiatan</h1>
        <p class="text-slate-400 text-sm mt-1">Tambah sub-kegiatan untuk kegiatan {{ $kegiatan->kode }} - {{ $kegiatan->nama }}</p>
    </div>
    <a href="{{ route('skpd.program.index') }}"
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali
    </a>
</div>

<!-- Error Messages -->
@if ($errors->any())
<div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400">
    <div class="flex items-start">
        <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<!-- Form Card -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50">
    <form action="{{ route('skpd.subkegiatan.store') }}" method="POST">
        @csrf
        
        <input type="hidden" name="kegiatan_id" value="{{ $kegiatan->id }}">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Program Information -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-300 mb-2">Program</label>
                <input type="text" value="{{ $program->kode }} - {{ $program->nama }}" readonly
                    class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-lg p-3 focus:ring-purple-500 focus:border-purple-500 cursor-not-allowed">
            </div>

            <!-- Kegiatan Information -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-slate-300 mb-2">Kegiatan</label>
                <input type="text" value="{{ $kegiatan->kode }} - {{ $kegiatan->nama }}" readonly
                    class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-lg p-3 focus:ring-purple-500 focus:border-purple-500 cursor-not-allowed">
            </div>

            <!-- Kode Sub-kegiatan -->
            <div>
                <label for="kode" class="block text-sm font-medium text-slate-300 mb-2">Kode Sub-kegiatan <span class="text-red-400">*</span></label>
                <input type="text" id="kode" name="kode" value="{{ old('kode') }}" required
                    class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-lg p-3 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-colors"
                    placeholder="Contoh: 1.01.01.001">
                <p class="text-slate-400 text-xs mt-1">Kode unik untuk sub-kegiatan ini</p>
            </div>

            <!-- Nama Sub-kegiatan -->
            <div>
                <label for="nama" class="block text-sm font-medium text-slate-300 mb-2">Nama Sub-kegiatan <span class="text-red-400">*</span></label>
                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                    class="w-full bg-slate-700/50 border border-slate-600 text-white rounded-lg p-3 focus:ring-purple-500 focus:border-purple-500 focus:outline-none transition-colors"
                    placeholder="Masukkan nama sub-kegiatan">
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 mt-6 pt-6 border-t border-slate-700/50">
            <a href="{{ route('skpd.program.index') }}"
                class="px-6 py-2.5 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 text-white hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-500/25">
                <i class="fas fa-save mr-2"></i>
                Simpan Sub-kegiatan
            </button>
        </div>
    </form>
</div>
@endsection