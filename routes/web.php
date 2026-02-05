<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\SkpdDashboardController;
use App\Http\Controllers\PptkDashboardController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Superadmin Routes (Protected)
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [SuperadminController::class, 'profile'])->name('profile');
    Route::post('/profile', [SuperadminController::class, 'updateProfile'])->name('profile.update');

    // SKPD Routes
    Route::prefix('skpd')->name('skpd.')->group(function () {
        Route::get('/', [SkpdController::class, 'index'])->name('index');
        Route::get('/create', [SkpdController::class, 'create'])->name('create');
        Route::post('/', [SkpdController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [SkpdController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SkpdController::class, 'update'])->name('update');
        Route::delete('/{id}', [SkpdController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/create-user', [SkpdController::class, 'createUser'])->name('create-user');
        Route::post('/{id}/reset-password', [SkpdController::class, 'resetPassword'])->name('reset-password');
    });
});

// SKPD Routes (Protected)
Route::prefix('skpd')->name('skpd.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [SkpdDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [SkpdDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [SkpdDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/rfk', [SkpdDashboardController::class, 'rfkIndex'])->name('rfk.index');

    // Bidang Routes
    Route::prefix('bidang')->name('bidang.')->group(function () {
        Route::get('/', [SkpdDashboardController::class, 'bidangIndex'])->name('index');
        Route::get('/create', [SkpdDashboardController::class, 'bidangCreate'])->name('create');
        Route::post('/', [SkpdDashboardController::class, 'bidangStore'])->name('store');
        Route::get('/{id}/edit', [SkpdDashboardController::class, 'bidangEdit'])->name('edit');
        Route::put('/{id}', [SkpdDashboardController::class, 'bidangUpdate'])->name('update');
        Route::delete('/{id}', [SkpdDashboardController::class, 'bidangDestroy'])->name('destroy');
    });

    // PPTK Routes
    Route::prefix('pptk')->name('pptk.')->group(function () {
        Route::get('/', [SkpdDashboardController::class, 'pptkIndex'])->name('index');
        Route::get('/create', [SkpdDashboardController::class, 'pptkCreate'])->name('create');
        Route::post('/', [SkpdDashboardController::class, 'pptkStore'])->name('store');
        Route::get('/{id}/edit', [SkpdDashboardController::class, 'pptkEdit'])->name('edit');
        Route::put('/{id}', [SkpdDashboardController::class, 'pptkUpdate'])->name('update');
        Route::delete('/{id}', [SkpdDashboardController::class, 'pptkDestroy'])->name('destroy');
        Route::post('/{id}/create-user', [SkpdDashboardController::class, 'pptkCreateUser'])->name('create-user');
        Route::post('/{id}/reset-password', [SkpdDashboardController::class, 'pptkResetPassword'])->name('reset-password');
    });

    // Program Routes (Data Rekening Belanja)
    Route::prefix('program')->name('program.')->group(function () {
        Route::get('/', [SkpdDashboardController::class, 'programIndex'])->name('index');
        Route::get('/create', [SkpdDashboardController::class, 'programCreate'])->name('create');
        Route::post('/', [SkpdDashboardController::class, 'programStore'])->name('store');
        Route::get('/{id}/edit', [SkpdDashboardController::class, 'programEdit'])->name('edit');
        Route::put('/{id}', [SkpdDashboardController::class, 'programUpdate'])->name('update');
        Route::delete('/{id}', [SkpdDashboardController::class, 'programDestroy'])->name('destroy');
    });

    // Sub-kegiatan Routes
    Route::prefix('subkegiatan')->name('subkegiatan.')->group(function () {
        Route::post('/update-pptk', [SkpdDashboardController::class, 'updateSubkegiatanPptk'])->name('updatePptk');
    });
});

// PPTK Routes (Protected)
Route::prefix('pptk')->name('pptk.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PptkDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PptkDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [PptkDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/subkegiatan', [PptkDashboardController::class, 'subkegiatanIndex'])->name('subkegiatan.index');

    // Uraian Routes
    Route::prefix('uraian')->name('uraian.')->group(function () {
        Route::get('/create/{subkegiatan_id}', [PptkDashboardController::class, 'uraianCreate'])->name('create');
        Route::post('/store', [PptkDashboardController::class, 'uraianStore'])->name('store');
        Route::get('/{id}/edit', [PptkDashboardController::class, 'uraianEdit'])->name('edit');
        Route::put('/{id}', [PptkDashboardController::class, 'uraianUpdate'])->name('update');
        Route::delete('/{id}', [PptkDashboardController::class, 'uraianDestroy'])->name('destroy');
        Route::post('/{id}/anggaran-kas', [PptkDashboardController::class, 'saveAnggaranKas'])->name('anggaran-kas');
    });
});
