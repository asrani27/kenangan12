<p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">
    Menu Utama</p>

<ul class="space-y-1">
    <li>
        <a href="{{ route('pptk.dashboard') }}"
            class="menu-item {{ request()->routeIs('pptk.dashboard') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-white">
            <i class="fas fa-home w-5 text-purple-400"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('pptk.subkegiatan.index') }}"
            class="menu-item {{ request()->routeIs('pptk.subkegiatan.*') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-slate-300">
            <i class="fas fa-tasks w-5 text-slate-400"></i>
            <span class="ml-3 font-medium">Subkegiatan</span>
        </a>
    </li>
    <li>
        <a href="{{ route('pptk.realisasi.index') }}"
            class="menu-item {{ request()->routeIs('pptk.realisasi.*') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-slate-300">
            <i class="fas fa-chart-line w-5 text-slate-400"></i>
            <span class="ml-3 font-medium">Realisasi</span>
        </a>
    </li>
</ul>
