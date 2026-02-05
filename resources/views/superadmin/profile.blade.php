@extends('layouts.app')

@section('title', 'Profil - KENANGAN')

@section('header-title', 'Profil')

@push('header-subtitle')
<p class="text-sm text-slate-400">Ubah kata sandi Anda</p>
@endpush

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Profile Card -->
    <div
        class="card-gradient rounded-2xl p-6 lg:p-8 border border-slate-700/50">
        <!-- Profile Header -->
        <div
            class="flex items-center space-x-4 mb-8 pb-8 border-b border-slate-700/50">
            <div
                class="w-20 h-20 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-3xl text-white"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">
                    {{ auth()->user()->username ?? 'Superadmin' }}
                </h3>
                <p class="text-slate-400">Administrator</p>
            </div>
        </div>

        <!-- Password Change Form -->
        <form method="POST" action="{{ route('superadmin.profile.update') }}" class="space-y-6">
            @csrf

            @if(session('success'))
            <div class="flex items-center p-4 rounded-xl bg-green-500/10 border border-green-500/30">
                <i class="fas fa-check-circle text-green-400 text-lg mr-3"></i>
                <p class="text-green-400 text-sm">{{ session('success') }}</p>
            </div>
            @endif

            @if($errors->any())
            <div class="flex items-start p-4 rounded-xl bg-red-500/10 border border-red-500/30">
                <i class="fas fa-exclamation-circle text-red-400 text-lg mr-3 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-red-400 text-sm font-medium mb-2">Terjadi
                        kesalahan:</p>
                    <ul class="text-red-300 text-xs space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Current Password -->
            <div>
                <label for="current_password"
                    class="block text-sm font-medium text-slate-300 mb-2">
                    <i class="fas fa-lock mr-2"></i>Kata Sandi Saat Ini
                </label>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password"
                        class="form-input w-full px-4 py-3 rounded-xl border" placeholder="Masukkan kata sandi saat ini"
                        required>
                    <button type="button" onclick="togglePasswordVisibility('current_password')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-purple-400 transition-colors">
                        <i class="fas fa-eye" id="current_password_icon"></i>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label for="password"
                    class="block text-sm font-medium text-slate-300 mb-2">
                    <i class="fas fa-key mr-2"></i>Kata Sandi Baru
                </label>
                <div class="relative">
                    <input type="password" id="password" name="password"
                        class="form-input w-full px-4 py-3 rounded-xl border" placeholder="Masukkan kata sandi baru"
                        required minlength="8">
                    <button type="button" onclick="togglePasswordVisibility('password')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-purple-400 transition-colors">
                        <i class="fas fa-eye" id="password_icon"></i>
                    </button>
                </div>
                <p class="text-xs text-slate-400 mt-2">
                    Minimal 8 karakter
                </p>
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation"
                    class="block text-sm font-medium text-slate-300 mb-2">
                    <i class="fas fa-check-circle mr-2"></i>Konfirmasi Kata Sandi Baru
                </label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-input w-full px-4 py-3 rounded-xl border" placeholder="Ulangi kata sandi baru"
                        required minlength="8">
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-purple-400 transition-colors">
                        <i class="fas fa-eye" id="password_confirmation_icon"></i>
                    </button>
                </div>
            </div>

            <!-- Password Strength Indicator -->
            <div class="p-4 rounded-xl bg-slate-700/30">
                <p class="text-xs font-medium text-slate-400 mb-2">Kekuatan
                    Kata Sandi:</p>
                <div class="flex items-center space-x-1">
                    <div id="strength-1" class="h-1 flex-1 rounded-full bg-slate-600"></div>
                    <div id="strength-2" class="h-1 flex-1 rounded-full bg-slate-600"></div>
                    <div id="strength-3" class="h-1 flex-1 rounded-full bg-slate-600"></div>
                    <div id="strength-4" class="h-1 flex-1 rounded-full bg-slate-600"></div>
                </div>
                <p id="strength-text" class="text-xs text-slate-400 mt-2">
                    Masukkan kata sandi untuk melihat kekuatannya
                </p>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-medium hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg shadow-purple-500/30 flex items-center justify-center space-x-2">
                    <i class="fas fa-save"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle Password Visibility
    function togglePasswordVisibility(fieldId) {
        const input = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Password Strength Checker
    document.getElementById('password').addEventListener('input', function(e) {
        const password = e.target.value;
        let strength = 0;
        
        // Check length
        if (password.length >= 8) strength++;
        
        // Check for uppercase
        if (/[A-Z]/.test(password)) strength++;
        
        // Check for numbers
        if (/[0-9]/.test(password)) strength++;
        
        // Check for special characters
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        
        // Update indicator
        const bars = [
            document.getElementById('strength-1'),
            document.getElementById('strength-2'),
            document.getElementById('strength-3'),
            document.getElementById('strength-4')
        ];
        
        const text = document.getElementById('strength-text');
        const colors = ['#22c55e', '#eab308', '#f97316', '#ef4444'];
        const labels = ['Sangat Kuat', 'Kuat', 'Sedang', 'Lemah'];
        
        // Reset all bars
        bars.forEach(bar => {
            bar.style.backgroundColor = '#475569';
        });
        
        if (password.length > 0) {
            for (let i = 0; i < strength; i++) {
                bars[i].style.backgroundColor = colors[Math.min(i, 3)];
            }
            
            text.textContent = labels[Math.min(strength - 1, 3)];
            text.style.color = colors[Math.min(strength - 1, 3)];
        } else {
            text.textContent = 'Masukkan kata sandi untuk melihat kekuatannya';
            text.style.color = '';
        }
    });
</script>
@endpush