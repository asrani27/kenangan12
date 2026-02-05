@extends('layouts.app')

@section('title', 'Kelola Bidang - KENANGAN')

@section('header-title', 'Kelola Bidang')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Data Bidang</h1>
        <p class="text-slate-400 text-sm mt-1">Kelola data bidang untuk {{ $skpd->nama }}</p>
    </div>
    <a href="{{ route('skpd.bidang.create') }}"
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-500/25">
        <i class="fas fa-plus mr-2"></i>
        Tambah Bidang
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

<!-- Bidang Table -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-slate-700/50">
                    <th class="pb-3 text-sm font-semibold text-slate-400 w-20">No</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400">Nama Bidang</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 hidden sm:table-cell">Tanggal Dibuat</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse($bidangs as $index => $bidang)
                <tr>
                    <td class="py-4 text-sm text-slate-300">{{ $index + 1 }}</td>
                    <td class="py-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mr-3">
                                <i class="fas fa-sitemap text-xs text-white"></i>
                            </div>
                            <span class="text-sm text-white font-medium">{{ $bidang->nama }}</span>
                        </div>
                    </td>
                    <td class="py-4 text-sm text-slate-300 hidden sm:table-cell">
                        {{ $bidang->created_at ? $bidang->created_at->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td class="py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('skpd.bidang.edit', $bidang->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $bidang->id }}, '{{ $bidang->nama }}')"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-slate-700/50 flex items-center justify-center mb-4">
                                <i class="fas fa-inbox text-2xl text-slate-500"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Belum ada data bidang</p>
                            <a href="{{ route('skpd.bidang.create') }}"
                                class="text-purple-400 text-sm hover:text-purple-300 mt-2">
                                Tambah bidang sekarang
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-md w-full transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hapus Bidang?</h3>
            <p class="text-slate-400 text-sm mb-6">
                Apakah Anda yakin ingin menghapus bidang "<span id="bidangName" class="text-white font-medium"></span>"?
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

<script>
    function confirmDelete(id, name) {
        document.getElementById('bidangName').textContent = name;
        document.getElementById('deleteForm').action = '/skpd/bidang/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>
@endsection