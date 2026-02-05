@extends('layouts.app')

@section('title', 'Edit PPTK - KENANGAN')

@section('header-title', 'Edit PPTK')

@section('content')
<!-- Page Header -->
<div class="mb-6">
    <a href="{{ route('skpd.pptk.index') }}" class="inline-flex items-center text-slate-400 hover:text-white transition-colors mb-4">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Data PPTK
    </a>
    <h1 class="text-2xl font-bold text-white">Edit PPTK</h1>
    <p class="text-slate-400 text-sm mt-1">Perbarui informasi PPTK di bawah ini</p>
</div>

<!-- Edit Form -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-2xl">
    <form action="{{ route('skpd.pptk.update', $pptk->id) }}" method="POST">
        @csrf
        @method('PUT')

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

        <!-- Current PPTK Info -->
        <div class="mb-6 p-4 rounded-lg bg-slate-800/30 border border-slate-700/50">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mr-3">
                    <i class="fas fa-user text-sm text-white"></i>
                </div>
                <div>
                    <p class="text-sm text-slate-400">PPTK saat ini:</p>
                    <p class="text-white font-medium">{{ $pptk->nama_pptk }}</p>
                </div>
            </div>
        </div>

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

        <!-- NIP PPTK -->
        <div class="mb-6">
            <label for="nip_pptk" class="block text-sm font-medium text-slate-300 mb-2">
                NIP PPTK <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <i class="fas fa-id-card"></i>
                </span>
                <input type="text"
                    id="nip_pptk"
                    name="nip_pptk"
                    value="{{ old('nip_pptk', $pptk->nip_pptk) }}"
                    class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-colors"
                    placeholder="Contoh: 198001012015011001"
                    required>
            </div>
            <p class="mt-1.5 text-xs text-slate-500">Nomor Induk Pegawai PPTK</p>
        </div>

        <!-- Nama PPTK -->
        <div class="mb-6">
            <label for="nama_pptk" class="block text-sm font-medium text-slate-300 mb-2">
                Nama PPTK <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <i class="fas fa-user"></i>
                </span>
                <input type="text"
                    id="nama_pptk"
                    name="nama_pptk"
                    value="{{ old('nama_pptk', $pptk->nama_pptk) }}"
                    class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700/50 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-colors"
                    placeholder="Contoh: Ahmad Fauzi, S.Kom"
                    required>
            </div>
            <p class="mt-1.5 text-xs text-slate-500">Nama lengkap PPTK dengan gelar</p>
        </div>

        <!-- Bidang -->
        <div class="mb-6">
            <label for="bidang_id" class="block text-sm font-medium text-slate-300 mb-2">
                Bidang <span class="text-red-400">*</span>
            </label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-500">
                    <i class="fas fa-sitemap"></i>
                </span>
                <select id="bidang_id"
                    name="bidang_id"
                    class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border border-slate-700/50 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 transition-colors appearance-none cursor-pointer"
                    required>
                    <option value="">-- Pilih Bidang --</option>
                    @foreach($bidangs as $bidang)
                    <option value="{{ $bidang->id }}" {{ old('bidang_id', $pptk->bidang_id) == $bidang->id ? 'selected' : '' }}>
                        {{ $bidang->nama }}
                    </option>
                    @endforeach
                </select>
                <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 pointer-events-none">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </div>
            <p class="mt-1.5 text-xs text-slate-500">Pilih bidang tempat PPTK bertugas</p>
        </div>

        <!-- Metadata Info -->
        <div class="mb-6 p-4 rounded-lg bg-slate-800/30 border border-slate-700/50">
            <h4 class="text-sm font-medium text-slate-300 mb-3">Informasi Tambahan</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-slate-500">Dibuat Tanggal:</p>
                    <p class="text-white">{{ $pptk->created_at ? $pptk->created_at->format('d/m/Y H:i') : '-' }}</p>
                </div>
                <div>
                    <p class="text-slate-500">Terakhir Diperbarui:</p>
                    <p class="text-white">{{ $pptk->updated_at ? $pptk->updated_at->format('d/m/Y H:i') : '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-700/50">
            <a href="{{ route('skpd.pptk.index') }}"
                class="inline-flex items-center px-4 py-2.5 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition-colors">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-500/25">
                <i class="fas fa-save mr-2"></i>
                Simpan Perubahan
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
                Perubahan data PPTK akan berdampak pada seluruh sistem yang menggunakan PPTK tersebut. Pastikan untuk memverifikasi data sebelum menyimpan.
            </p>
        </div>
    </div>
</div>
@endsection