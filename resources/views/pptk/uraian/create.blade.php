@extends('layouts.app')

@section('title', 'Tambah Uraian - KENANGAN')

@section('header-title', 'Tambah Uraian')

@section('content')
<!-- Page Header -->
<div class="flex items-center mb-6">
    <a href="{{ route('pptk.subkegiatan.index') }}" 
        class="text-slate-400 hover:text-white mr-3 transition-colors">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-white">Tambah Uraian</h1>
        <p class="text-slate-400 text-sm mt-1">Subkegiatan: {{ $subkegiatan->nama }}</p>
    </div>
</div>

<!-- Form Card -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50">
    <form action="{{ route('pptk.uraian.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <input type="hidden" name="subkegiatan_id" value="{{ $subkegiatan->id }}">

        <!-- Subkegiatan Info -->
        <div class="bg-slate-800/50 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs text-slate-400 uppercase tracking-wide">Kode Subkegiatan</label>
                    <p class="text-sm text-white font-mono mt-1">{{ $subkegiatan->kode }}</p>
                </div>
                <div>
                    <label class="text-xs text-slate-400 uppercase tracking-wide">Tahun</label>
                    <p class="text-sm text-white font-semibold mt-1">{{ $subkegiatan->tahun }}</p>
                </div>
            </div>
        </div>

        <!-- Kode Rekening -->
        <div>
            <label for="kode_rekening" class="block text-sm font-medium text-slate-300 mb-2">
                Kode Rekening <span class="text-red-400">*</span>
            </label>
            <input type="text" 
                id="kode_rekening" 
                name="kode_rekening" 
                value="{{ old('kode_rekening') }}"
                placeholder="Contoh: 5.1.2.01.01.01.001"
                class="w-full bg-slate-700 text-white rounded-lg border border-slate-600 px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-slate-500"
                required>
            @error('kode_rekening')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama Uraian -->
        <div>
            <label for="nama" class="block text-sm font-medium text-slate-300 mb-2">
                Nama Uraian <span class="text-red-400">*</span>
            </label>
            <input type="text" 
                id="nama" 
                name="nama" 
                value="{{ old('nama') }}"
                placeholder="Masukkan nama uraian"
                class="w-full bg-slate-700 text-white rounded-lg border border-slate-600 px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-slate-500"
                required>
            @error('nama')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- DPA -->
        <div>
            <label for="dpa" class="block text-sm font-medium text-slate-300 mb-2">
                DPA (Dana Pelaksanaan Anggaran)
            </label>
            <input type="text" 
                id="dpa_display" 
                placeholder="0"
                class="w-full bg-slate-700 text-white rounded-lg border border-slate-600 px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 placeholder-slate-500"
                oninput="formatNumber(this)">
            <input type="hidden" 
                id="dpa" 
                name="dpa" 
                value="{{ old('dpa') }}">
            @error('dpa')
                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Script for Number Formatting -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize display value if there's an old value
                const dpaInput = document.getElementById('dpa');
                const dpaDisplay = document.getElementById('dpa_display');
                
                if (dpaInput.value) {
                    dpaDisplay.value = formatNumberValue(dpaInput.value);
                }
            });

            function formatNumber(input) {
                // Remove all non-numeric characters except dots (for formatting)
                let value = input.value.replace(/\./g, '').replace(/,/g, '');
                
                // If empty, set to 0
                if (value === '') {
                    value = '0';
                }
                
                // Convert to number
                let number = parseFloat(value);
                
                // Update hidden input
                document.getElementById('dpa').value = number;
                
                // Format display value
                input.value = formatNumberValue(number);
            }

            function formatNumberValue(number) {
                return new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(number);
            }
        </script>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-700/50">
            <a href="{{ route('pptk.subkegiatan.index') }}" 
                class="px-6 py-3 rounded-lg border border-slate-600 text-slate-300 hover:bg-slate-700 hover:text-white transition-all">
                Batal
            </a>
            <button type="submit" 
                class="px-6 py-3 rounded-lg bg-gradient-to-r from-purple-500 to-indigo-600 text-white hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg shadow-purple-500/20">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
</div>
@endsection