<p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3">
    Menu Utama</p>

<ul class="space-y-1">
    <li>
        <a href="{{ route('superadmin.dashboard') }}"
            class="menu-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-white">
            <i class="fas fa-home w-5 text-purple-400"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>
    </li>
            <li>
                <a href="{{ route('superadmin.skpd.index') }}"
                    class="menu-item {{ request()->routeIs('superadmin.skpd.*') ? 'active' : '' }} flex items-center px-3 py-3 rounded-lg text-slate-300">
                    <i class="fas fa-building w-5 text-slate-400"></i>
                    <span class="ml-3 font-medium">SKPD</span>
                </a>
            </li>
</ul>

<p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 px-3 mt-8">
    Pengaturan</p>

<ul class="space-y-1">
    <li>
        <a href="#" class="menu-item flex items-center px-3 py-3 rounded-lg text-slate-300">
            <i class="fas fa-cog w-5 text-slate-400"></i>
            <span class="ml-3 font-medium">Pengaturan</span>
        </a>
    </li>
</ul>