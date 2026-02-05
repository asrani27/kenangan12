<!-- Top Navigation -->
<nav class="bg-slate-900/80 backdrop-blur-md border-b border-slate-700 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-3">
                    <img src="/logo/pemko.png" class="h-10 w-10 rounded-lg" alt="Logo">
                    <span class="text-xl font-bold gradient-text">KENANGAN</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="/"
                    class="nav-link {{ $activePage === 'dashboard' ? 'active' : '' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i>Dashboard
                </a>
                <a href="{{ route('login') }}"
                    class="nav-link {{ $activePage === 'login' ? 'active' : '' }} px-3 py-2 text-sm font-medium transition-colors duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </a>
            </div>

            <!-- Mobile menu button -->
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-slate-800 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="hidden md:hidden bg-slate-900/95 border-t border-slate-700">
        <div class="px-4 py-3 space-y-2">
            <a href="/"
                class="nav-link {{ $activePage === 'dashboard' ? 'active' : '' }} block px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors">
                <i class="fas fa-home mr-2"></i>Dashboard
            </a>
            <a href="{{ route('login') }}"
                class="nav-link {{ $activePage === 'login' ? 'active' : '' }} block px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors">
                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
            </a>
        </div>
    </div>
</nav>