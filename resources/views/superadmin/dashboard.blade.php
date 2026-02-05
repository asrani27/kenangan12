@extends('layouts.app')

@section('title', 'Dashboard Superadmin - KENANGAN')

@section('header-title', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <!-- Total Users -->
    <div
        class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Total Users</p>
                <p class="text-3xl font-bold text-white mt-2">1,234
                </p>
                <p class="text-green-400 text-sm mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>+12% dari bulan lalu
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                <i class="fas fa-users text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Total SKPD -->
    <div
        class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Total SKPD</p>
                <p class="text-3xl font-bold text-white mt-2">56
                </p>
                <p class="text-green-400 text-sm mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>+5% dari bulan lalu
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center">
                <i class="fas fa-building text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Total PPTK -->
    <div
        class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Total PPTK</p>
                <p class="text-3xl font-bold text-white mt-2">234
                </p>
                <p class="text-yellow-400 text-sm mt-2">
                    <i class="fas fa-minus mr-1"></i>Sama seperti bulan lalu
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                <i class="fas fa-user-tie text-2xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- Active Sessions -->
    <div
        class="stat-card card-gradient rounded-xl p-6 border border-slate-700/50">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-400 text-sm font-medium">
                    Sesi Aktif</p>
                <p class="text-3xl font-bold text-white mt-2">89
                </p>
                <p class="text-green-400 text-sm mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>+8% dari jam ini
                </p>
            </div>
            <div
                class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                <i class="fas fa-signal text-2xl text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Tables Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Activity -->
    <div
        class="card-gradient rounded-xl p-6 border border-slate-700/50">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-clock text-purple-400 mr-2"></i>Aktivitas Terbaru
        </h3>
        <div class="space-y-4">
            <div class="flex items-start space-x-3 p-3 rounded-lg activity-item">
                <div
                    class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-plus text-green-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-medium">User
                        baru terdaftar</p>
                    <p class="text-xs text-slate-400 truncate">
                        Ahmad Suhendi bergabung sebagai SKPD</p>
                    <p class="text-xs text-slate-500 mt-1">2
                        menit yang lalu</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 rounded-lg activity-item">
                <div
                    class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-blue-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-medium">
                        Laporan baru dibuat</p>
                    <p class="text-xs text-slate-400 truncate">
                        Laporan bulanan Q1 2026</p>
                    <p class="text-xs text-slate-500 mt-1">15
                        menit yang lalu</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 rounded-lg activity-item">
                <div
                    class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cog text-purple-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-medium">
                        Sistem diperbarui</p>
                    <p class="text-xs text-slate-400 truncate">
                        Versi 2.5.0 berhasil di-deploy</p>
                    <p class="text-xs text-slate-500 mt-1">1
                        jam yang lalu</p>
                </div>
            </div>
            <div class="flex items-start space-x-3 p-3 rounded-lg activity-item">
                <div
                    class="w-10 h-10 rounded-full bg-yellow-500/20 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-edit text-yellow-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-medium">Data
                        SKPD diperbarui</p>
                    <p class="text-xs text-slate-400 truncate">
                        Dinas Kesehatan memperbarui profil</p>
                    <p class="text-xs text-slate-500 mt-1">2
                        jam yang lalu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div
        class="card-gradient rounded-xl p-6 border border-slate-700/50">
        <h3 class="text-lg font-semibold text-white mb-4">
            <i class="fas fa-bolt text-purple-400 mr-2"></i>Aksi Cepat
        </h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="#"
                class="flex flex-col items-center justify-center p-4 rounded-lg quick-action-item hover:bg-purple-500/20 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-plus text-xl text-white"></i>
                </div>
                <span class="text-sm font-medium text-white">Tambah
                    User</span>
            </a>
            <a href="#"
                class="flex flex-col items-center justify-center p-4 rounded-lg quick-action-item hover:bg-blue-500/20 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-building text-xl text-white"></i>
                </div>
                <span class="text-sm font-medium text-white">Tambah
                    SKPD</span>
            </a>
            <a href="#"
                class="flex flex-col items-center justify-center p-4 rounded-lg quick-action-item hover:bg-amber-500/20 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-tie text-xl text-white"></i>
                </div>
                <span class="text-sm font-medium text-white">Tambah
                    PPTK</span>
            </a>
            <a href="#"
                class="flex flex-col items-center justify-center p-4 rounded-lg quick-action-item hover:bg-green-500/20 transition-all group">
                <div
                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-bar text-xl text-white"></i>
                </div>
                <span class="text-sm font-medium text-white">Lihat
                    Laporan</span>
            </a>
        </div>
    </div>
</div>

<!-- Recent Users Table -->
<div
    class="card-gradient rounded-xl p-6 border border-slate-700/50">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-white">
            <i class="fas fa-users text-purple-400 mr-2"></i>User Terbaru
        </h3>
        <a href="#" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr
                    class="text-left border-b border-slate-700/50">
                    <th
                        class="pb-3 text-sm font-semibold text-slate-400">
                        User</th>
                    <th
                        class="pb-3 text-sm font-semibold text-slate-400 hidden md:table-cell">
                        Email</th>
                    <th
                        class="pb-3 text-sm font-semibold text-slate-400">
                        Role</th>
                    <th
                        class="pb-3 text-sm font-semibold text-slate-400 hidden sm:table-cell">
                        Status</th>
                </tr>
            </thead>
            <tbody
                class="divide-y divide-slate-700/50">
                <tr>
                    <td class="py-3">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                                <i class="fas fa-user text-xs text-white"></i>
                            </div>
                            <span
                                class="text-sm text-white font-medium">Ahmad
                                Suhendi</span>
                        </div>
                    </td>
                    <td
                        class="py-3 text-sm text-slate-300 hidden md:table-cell">
                        ahmad@kota.go.id</td>
                    <td class="py-3">
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full bg-blue-500/20 text-blue-400">SKPD</span>
                    </td>
                    <td class="py-3 hidden sm:table-cell">
                        <span class="flex items-center text-sm text-green-400">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>Aktif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="py-3">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                                <i class="fas fa-user text-xs text-white"></i>
                            </div>
                            <span
                                class="text-sm text-white font-medium">Budi
                                Santoso</span>
                        </div>
                    </td>
                    <td
                        class="py-3 text-sm text-slate-300 hidden md:table-cell">
                        budi@kota.go.id</td>
                    <td class="py-3">
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full bg-amber-500/20 text-amber-400">PPTK</span>
                    </td>
                    <td class="py-3 hidden sm:table-cell">
                        <span class="flex items-center text-sm text-green-400">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>Aktif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="py-3">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center">
                                <i class="fas fa-user text-xs text-white"></i>
                            </div>
                            <span
                                class="text-sm text-white font-medium">Citra
                                Dewi</span>
                        </div>
                    </td>
                    <td
                        class="py-3 text-sm text-slate-300 hidden md:table-cell">
                        citra@kota.go.id</td>
                    <td class="py-3">
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full bg-green-500/20 text-green-400">SKPD</span>
                    </td>
                    <td class="py-3 hidden sm:table-cell">
                        <span class="flex items-center text-sm text-green-400">
                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>Aktif
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection