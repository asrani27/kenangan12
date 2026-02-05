<!-- Fixed Sidebar -->
<aside id="sidebar"
    class="sidebar fixed lg:fixed z-50 w-72 h-screen card-gradient transform -translate-x-full lg:translate-x-0 flex flex-col">
    <!-- Logo -->
    <div class="p-3 border-b border-slate-700/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center">
                    <i class="fas fa-shield-halved text-xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold gradient-text">KENANGAN</h1>
                    <p class="text-xs text-slate-400">Superadmin Panel
                    </p>
                </div>
            </div>
            <button class="text-slate-400 hover:text-white lg:hidden" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <!-- Scrollable Navigation Menu -->
    <nav class="flex-1 overflow-y-auto sidebar-scroll p-4">
        @if(auth()->check() && auth()->user()->roles->isNotEmpty())
        @php
        $role = auth()->user()->roles->first()->name;
        @endphp
        @if(strtolower($role) == 'superadmin')
        @include('layouts.menus.menu_superadmin')
        @elseif(strtolower($role) == 'skpd')
        @include('layouts.menus.menu_skpd')
        @elseif(strtolower($role) == 'pptk')
        @include('layouts.menus.menu_pptk')
        @else
        @include('layouts.menus.menu_superadmin')
        @endif
        @else
        @include('layouts.menus.menu_superadmin')
        @endif
    </nav>

    <!-- User Profile at Bottom -->
    <div class="border-t border-slate-700/50">
        <div class="p-2">
            @php
            $profileRoute = 'superadmin.profile';
            $roleName = 'Administrator';

            if(auth()->check() && auth()->user()->roles->isNotEmpty()) {
            $role = strtolower(auth()->user()->roles->first()->name);
            if($role == 'superadmin') {
            $profileRoute = 'superadmin.profile';
            $roleName = 'Superadmin';
            } elseif($role == 'skpd') {
            $profileRoute = 'skpd.profile';
            $roleName = 'SKPD';
            } elseif($role == 'pptk') {
            $profileRoute = 'pptk.profile';
            $roleName = 'PPTK';
            }
            }
            @endphp
            <!-- User Profile Button (Clickable) -->
            <button onclick="toggleUserDropdown()"
                class="w-full flex items-center space-x-3 p-1.5 rounded-lg hover:bg-slate-700/30 transition-colors">
                <div
                    class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0 text-left">
                    <p class="text-xs font-semibold text-white truncate">{{
                        auth()->user()->username ?? 'Superadmin' }}</p>
                    <p class="text-[10px] text-slate-400 truncate">
                        {{ $roleName }}</p>
                </div>
                <i id="dropdown-arrow"
                    class="fas fa-chevron-down text-slate-400 text-xs transition-transform duration-300"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown" class="user-dropdown mt-2 space-y-1">
                <a href="{{ route($profileRoute) }}"
                    class="dropdown-item flex items-center px-4 py-3 rounded-lg text-purple-400 hover:bg-purple-500/10 transition-colors">
                    <i class="fas fa-user-circle w-5"></i>
                    <span class="ml-3 font-medium">Profil</span>
                </a>
                <button type="button" onclick="showLogoutModal(event)"
                    class="dropdown-item flex items-center w-full px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 transition-colors cursor-pointer">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="ml-3 font-medium">Keluar</span>
                </button>
            </div>
        </div>
    </div>
</aside>