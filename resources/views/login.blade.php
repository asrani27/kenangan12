<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - KENANGAN</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .input-field {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #a78bfa;
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.1);
            outline: none;
        }

        .input-field::placeholder {
            color: rgba(148, 163, 184, 0.6);
        }

        .nav-link:hover {
            color: #a78bfa;
        }

        .nav-link.active {
            color: #c4b5fd;
            border-bottom: 2px solid #a78bfa;
        }
    </style>
</head>

<body class="pattern-bg min-h-screen text-white">
    <div class="relative z-10">

        @include('layouts.navigation', ['activePage' => 'login'])

        <!-- Main Content -->
        <main class="min-h-[calc(100vh-16rem)] flex items-center justify-center px-4 py-12">
            <div class="w-full max-w-md">
                
                <!-- Login Card -->
                <div class="card-gradient rounded-2xl p-8 shadow-2xl">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 mb-4">
                            <i class="fas fa-user-lock text-3xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold mb-2 gradient-text">Selamat Datang</h1>
                        <p class="text-slate-400">Silakan masuk ke akun Anda</p>
                    </div>

                    <!-- Login Form -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                                <span class="text-red-400 text-sm">{{ $errors->first() }}</span>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('login.process') }}" method="POST">
                        @csrf

                        <!-- Username Field -->
                        <div class="mb-6">
                            <label for="username" class="block text-sm font-medium text-slate-300 mb-2">
                                <i class="fas fa-user mr-2"></i>Username
                            </label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                class="input-field w-full px-4 py-3 rounded-lg text-white"
                                placeholder="Masukkan username Anda"
                                required
                                autofocus>
                            @error('username')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                                <i class="fas fa-lock mr-2"></i>Password
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="input-field w-full px-4 py-3 pr-12 rounded-lg text-white"
                                    placeholder="Masukkan password Anda"
                                    required>
                                <button
                                    type="button"
                                    id="toggle-password"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white transition-colors">
                                    <i class="fas fa-eye" id="eye-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    id="remember"
                                    class="w-4 h-4 rounded border-slate-600 bg-slate-800 text-purple-600 focus:ring-purple-500 focus:ring-offset-slate-900">
                                <span class="ml-2 text-sm text-slate-300">Ingat saya</span>
                            </label>
                            <a href="#" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">
                                Lupa password?
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="flex items-center my-6">
                        <div class="flex-1 border-t border-slate-700"></div>
                        <span class="px-4 text-sm text-slate-400">atau</span>
                        <div class="flex-1 border-t border-slate-700"></div>
                    </div>

                    <!-- Back to Dashboard -->
                    <div class="text-center">
                        <p class="text-slate-400 text-sm">
                            Belum punya akun?
                            <a href="/" class="text-purple-400 hover:text-purple-300 font-medium transition-colors">
                                Kembali ke Dashboard
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="text-center mt-8">
                    <p class="text-slate-500 text-sm">
                        <i class="fas fa-shield-alt mr-2"></i>Login aman dengan enkripsi SSL
                    </p>
                </div>

            </div>
        </main>

        @include('layouts.footer')

    </div>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });

        // Toggle password visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    </script>

</body>

</html>