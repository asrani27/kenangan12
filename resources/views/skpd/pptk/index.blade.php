@extends('layouts.app')

@section('title', 'Kelola PPTK - KENANGAN')

@section('header-title', 'Kelola PPTK')

@section('content')
<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Data PPTK</h1>
        <p class="text-slate-400 text-sm mt-1">Kelola data PPTK untuk {{ $skpd->nama }}</p>
    </div>
    <a href="{{ route('skpd.pptk.create') }}"
        class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-lg hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg hover:shadow-purple-500/25">
        <i class="fas fa-plus mr-2"></i>
        Tambah PPTK
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

<!-- PPTK Table -->
<div class="card-gradient rounded-xl p-6 border border-slate-700/50">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="text-left border-b border-slate-700/50">
                    <th class="pb-3 text-sm font-semibold text-slate-400 w-20">No</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400">Nama PPTK</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 hidden md:table-cell">NIP</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 hidden lg:table-cell">Bidang</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 hidden sm:table-cell">Username</th>
                    <th class="pb-3 text-sm font-semibold text-slate-400 text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700/50">
                @forelse($pptks as $index => $pptk)
                <tr>
                    <td class="py-4 text-sm text-slate-300">{{ $index + 1 }}</td>
                    <td class="py-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mr-3">
                                <i class="fas fa-user text-xs text-white"></i>
                            </div>
                            <div>
                                <span class="text-sm text-white font-medium block">{{ $pptk->nama_pptk }}</span>
                                <span class="text-xs text-slate-400 md:hidden">{{ $pptk->nip_pptk }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 text-sm text-slate-300 hidden md:table-cell">{{ $pptk->nip_pptk }}</td>
                    <td class="py-4 text-sm text-slate-300 hidden lg:table-cell">
                        {{ $pptk->bidang ? $pptk->bidang->nama : '-' }}
                    </td>
                    <td class="py-4 text-sm text-slate-300 hidden sm:table-cell">
                        @if($pptk->user)
                            <div class="flex items-center">
                                <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center mr-2">
                                    <i class="fas fa-user-circle text-xs text-blue-400"></i>
                                </div>
                                <span>{{ $pptk->user->username }}</span>
                            </div>
                        @else
                            <span class="text-slate-500 italic">Belum ada user</span>
                        @endif
                    </td>
                    <td class="py-4">
                        <div class="flex items-center justify-center space-x-2">
                            @if($pptk->user)
                                <button onclick="confirmResetPassword({{ $pptk->id }}, '{{ $pptk->nama_pptk }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30 transition-colors"
                                    title="Reset Password">
                                    <i class="fas fa-key text-sm"></i>
                                </button>
                            @else
                                <button onclick="confirmCreateUser({{ $pptk->id }}, '{{ $pptk->nama_pptk }}')"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-500/20 text-green-400 hover:bg-green-500/30 transition-colors"
                                    title="Create User">
                                    <i class="fas fa-user-plus text-sm"></i>
                                </button>
                            @endif
                            <a href="{{ route('skpd.pptk.edit', $pptk->id) }}"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/20 text-blue-400 hover:bg-blue-500/30 transition-colors"
                                title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="confirmDelete({{ $pptk->id }}, '{{ $pptk->nama_pptk }}')"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors"
                                title="Hapus">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 rounded-full bg-slate-700/50 flex items-center justify-center mb-4">
                                <i class="fas fa-users text-2xl text-slate-500"></i>
                            </div>
                            <p class="text-slate-400 text-sm">Belum ada data PPTK</p>
                            <a href="{{ route('skpd.pptk.create') }}"
                                class="text-purple-400 text-sm hover:text-purple-300 mt-2">
                                Tambah PPTK sekarang
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
            <h3 class="text-xl font-bold text-white mb-2">Hapus PPTK?</h3>
            <p class="text-slate-400 text-sm mb-6">
                Apakah Anda yakin ingin menghapus PPTK "<span id="pptkName" class="text-white font-medium"></span>"?
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

<!-- Create User Confirmation Modal -->
<div id="createUserModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-md w-full transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-green-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-3xl text-green-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Buat User untuk PPTK?</h3>
            <p class="text-slate-400 text-sm mb-6">
                Apakah Anda yakin ingin membuat user untuk PPTK "<span id="pptkNameCreate" class="text-white font-medium"></span>"?
                Username akan dibuat otomatis dan password akan di-reset ke default.
            </p>
            <form id="createUserForm" action="" method="POST">
                @csrf
                @method('POST')
                <div class="flex items-center justify-center space-x-3">
                    <button type="button" onclick="closeCreateUserModal()"
                        class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors">
                        Buat User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Confirmation Modal -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="card-gradient rounded-xl p-6 border border-slate-700/50 max-w-md w-full transform transition-all">
        <div class="text-center">
            <div class="w-16 h-16 rounded-full bg-yellow-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-key text-3xl text-yellow-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Reset Password?</h3>
            <p class="text-slate-400 text-sm mb-6">
                Apakah Anda yakin ingin mereset password untuk PPTK "<span id="pptkNameReset" class="text-white font-medium"></span>"?
                Password akan di-reset ke password default.
            </p>
            <form id="resetPasswordForm" action="" method="POST">
                @csrf
                @method('POST')
                <div class="flex items-center justify-center space-x-3">
                    <button type="button" onclick="closeResetPasswordModal()"
                        class="px-4 py-2 rounded-lg bg-slate-700 text-white hover:bg-slate-600 transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-colors">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, name) {
        document.getElementById('pptkName').textContent = name;
        document.getElementById('deleteForm').action = '/skpd/pptk/' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }

    function confirmCreateUser(id, name) {
        document.getElementById('pptkNameCreate').textContent = name;
        document.getElementById('createUserForm').action = '/skpd/pptk/' + id + '/create-user';
        document.getElementById('createUserModal').classList.remove('hidden');
        document.getElementById('createUserModal').classList.add('flex');
    }

    function closeCreateUserModal() {
        document.getElementById('createUserModal').classList.add('hidden');
        document.getElementById('createUserModal').classList.remove('flex');
    }

    function confirmResetPassword(id, name) {
        document.getElementById('pptkNameReset').textContent = name;
        document.getElementById('resetPasswordForm').action = '/skpd/pptk/' + id + '/reset-password';
        document.getElementById('resetPasswordModal').classList.remove('hidden');
        document.getElementById('resetPasswordModal').classList.add('flex');
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.add('hidden');
        document.getElementById('resetPasswordModal').classList.remove('flex');
    }
</script>
@endsection