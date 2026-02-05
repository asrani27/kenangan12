<p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">
    Menu Utama</p>

<ul class="space-y-1">
    <li>
        <a href="{{ route('skpd.dashboard') }}"
            class="menu-item {{ request()->routeIs('skpd.dashboard') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-white">
            <i class="fas fa-home w-5 text-purple-400"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('skpd.bidang.index') }}"
            class="menu-item {{ request()->routeIs('skpd.bidang.*') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-slate-300">
            <i class="fas fa-file-alt w-5 text-slate-400"></i>
            <span class="ml-3 font-medium">Bidang</span>
        </a>
    </li>
    <li>
        <a href="{{ route('skpd.pptk.index') }}"
            class="menu-item {{ request()->routeIs('skpd.pptk.*') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-slate-300">
            <i class="fas fa-users w-5 text-slate-400"></i>
            <span class="ml-3 font-medium">PPTK</span>
        </a>
    </li>
    <li>
        <a href="{{ route('skpd.program.index') }}"
            class="menu-item {{ request()->routeIs('skpd.program.*') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-slate-300">
            <i class="fas fa-list-alt w-5 text-slate-400"></i>
            <span class="ml-3 font-medium">Data Rekening Belanja</span>
        </a>
    </li>
    <li>
        <a href="{{ route('skpd.rfk.index') }}" 
           class="menu-item {{ request()->routeIs('skpd.rfk.*') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-slate-300">
            <i class="fas fa-chart-line w-5 text-slate-400"></i>
            <span class="ml-3 font-medium">Laporan RFK</span>
        </a>
    </li>
</ul>