<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - KENANGAN')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Pattern Background */
        .pattern-bg {
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
            position: relative;
        }

        .pattern-bg::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        /* Card Gradient */
        .card-gradient {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-overlay {
            transition: opacity 0.3s ease-in-out;
        }

        /* Menu Item Styles */
        .menu-item {
            transition: all 0.2s ease;
        }

        .menu-item:hover {
            background: rgba(167, 139, 250, 0.1);
            border-left: 3px solid #a78bfa;
        }

        .menu-item.active {
            background: rgba(167, 139, 250, 0.15);
            border-left: 3px solid #a78bfa;
        }

        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(167, 139, 250, 0.2);
        }

        /* Custom Scrollbar for Sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(139, 92, 246, 0.5);
        }

        /* Activity Item Background */
        .activity-item {
            background: rgba(15, 23, 42, 0.5);
        }

        /* Quick Action Item Background */
        .quick-action-item {
            background: rgba(15, 23, 42, 0.5);
        }

        /* Dropdown Menu Styles */
        .user-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .user-dropdown.open {
            max-height: 500px;
        }

        .dropdown-item:hover {
            background: rgba(167, 139, 250, 0.1);
        }

        /* Form Input Styles */
        .form-input {
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .form-input {
            background: rgba(15, 23, 42, 0.5);
            border-color: rgba(51, 65, 85, 0.5);
            color: #e2e8f0;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        /* Modal Styles */
        #logout-modal {
            pointer-events: auto;
        }

        #logout-modal.hidden {
            pointer-events: none;
        }
    </style>
</head>

<body class="pattern-bg min-h-screen text-white overflow-x-hidden">
    <div class="relative z-10 flex min-h-screen">

        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebar-overlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-40 hidden lg:hidden"
            onclick="toggleSidebar()"></div>

        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-h-screen lg:ml-72">
            <!-- Top Header -->
            <header
                class="card-gradient border-b border-slate-700/50 px-4 lg:px-6 py-3 flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()"
                        class="lg:hidden text-white hover:text-purple-400 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-semibold text-white">@yield('header-title', 'Dashboard')</h2>
                        @stack('header-subtitle')
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Logout Button -->
                    <button onclick="showLogoutModal(event)"
                        class="relative p-2 text-slate-400 hover:text-red-400 transition-colors"
                        title="Keluar">
                        <i class="fas fa-sign-out-alt text-xl"></i>
                    </button>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="flex-1 p-4 lg:p-6 overflow-y-auto">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="card-gradient border-t border-slate-700/50 px-6 py-4">
                <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-slate-400">
                    <p>© 2026 KENANGAN. All rights reserved.</p>
                    <p class="mt-2 sm:mt-0">Panel v1.0.0</p>
                </div>
            </footer>
        </main>

    </div>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center">
        <!-- Modal Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="hideLogoutModal()"></div>

        <!-- Modal Content -->
        <div
            class="modal-content relative bg-slate-800 rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl border border-slate-700/50">
            <!-- Modal Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center">
                    <i class="fas fa-sign-out-alt text-3xl text-red-400"></i>
                </div>
            </div>

            <!-- Modal Title -->
            <h3 class="text-xl font-bold text-center text-white mb-2">
                Konfirmasi Keluar
            </h3>

            <!-- Modal Message -->
            <p class="text-center text-slate-300 mb-6">
                Apakah Anda yakin ingin keluar dari akun? Anda perlu login kembali untuk mengakses sistem.
            </p>

            <!-- Modal Buttons -->
            <div class="flex space-x-3">
                <button onclick="hideLogoutModal()"
                    class="flex-1 px-4 py-3 rounded-xl bg-slate-700/50 text-slate-300 font-medium hover:bg-slate-600/50 transition-colors">
                    Batal
                </button>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="button" onclick="confirmLogout()"
                        class="w-full px-4 py-3 rounded-xl bg-gradient-to-r from-red-500 to-red-600 text-white font-medium hover:from-red-600 hover:to-red-700 transition-all shadow-lg shadow-red-500/30">
                        Ya, Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle Sidebar
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
            if (overlay) {
                overlay.classList.toggle('hidden');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const toggleBtn = document.querySelector('[onclick="toggleSidebar()"]');
            
            if (window.innerWidth < 1024) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target) && !overlay.classList.contains('hidden')) {
                    toggleSidebar();
                }
            }
        });

        // Toggle User Dropdown
        window.toggleUserDropdown = function() {
            const dropdown = document.getElementById('user-dropdown');
            const arrow = document.getElementById('dropdown-arrow');
            
            if (dropdown && arrow) {
                dropdown.classList.toggle('open');
                
                if (dropdown.classList.contains('open')) {
                    arrow.style.transform = 'rotate(180deg)';
                } else {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        }

        // Close dropdown when clicking on dropdown items (except logout button)
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item');
            dropdownItems.forEach(function(item) {
                item.addEventListener('click', function(e) {
                    // Don't prevent default behavior
                    const logoutBtn = item.querySelector('button[onclick="showLogoutModal()"]');
                    
                    // Only close dropdown if it's NOT the logout button
                    if (!logoutBtn) {
                        setTimeout(function() {
                            const dropdown = document.getElementById('user-dropdown');
                            const arrow = document.getElementById('dropdown-arrow');
                            
                            if (dropdown && arrow) {
                                dropdown.classList.remove('open');
                                arrow.style.transform = 'rotate(0deg)';
                            }
                        }, 100);
                    }
                });
            });
        });

        // Show Logout Confirmation Modal
        window.showLogoutModal = function(e) {
            // Prevent event propagation if event object is provided
            if (e) {
                e.stopPropagation();
                e.preventDefault();
            }
            
            const modal = document.getElementById('logout-modal');
            const dropdown = document.getElementById('user-dropdown');
            const arrow = document.getElementById('dropdown-arrow');
            
            // Close the user dropdown first
            if (dropdown) {
                dropdown.classList.remove('open');
            }
            if (arrow) {
                arrow.style.transform = 'rotate(0deg)';
            }
            
            // Show the modal
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                console.log('Logout modal shown');
            }
        }

        // Hide Logout Confirmation Modal
        window.hideLogoutModal = function() {
            const modal = document.getElementById('logout-modal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Confirm Logout
        window.confirmLogout = function() {
            const logoutForm = document.getElementById('logout-form');
            if (logoutForm) {
                logoutForm.submit();
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('logout-modal');
            
            if (!modal.classList.contains('hidden')) {
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent && !modalContent.contains(event.target)) {
                    hideLogoutModal();
                }
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('logout-modal');
                if (!modal.classList.contains('hidden')) {
                    hideLogoutModal();
                }
            }
        });
    </script>

    @stack('scripts')

</body>

</html>