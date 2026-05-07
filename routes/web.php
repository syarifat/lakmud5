<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\PemateriController;
use App\Http\Controllers\Admin\KelompokController;

Route::get('/', function () {
    return view('welcome');
});

// Route bawaan Breeze kita biarkan sementara
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// ROUTE PUBLIK UNTUK PENDAFTARAN
Route::get('/daftar-lakmud', [PendaftaranController::class, 'index'])->name('pendaftar.biodata');
Route::post('/daftar-lakmud', [PendaftaranController::class, 'store'])->name('pendaftar.store');
// ==========================================
// PENGELOMPOKAN ROUTE BERDASARKAN ROLE
// ==========================================

// 1. Route Khusus Role: Pendaftar
Route::middleware(['auth', 'role:pendaftar'])->prefix('pendaftaran')->name('pendaftar.')->group(function () {
    Route::get('/biodata', function() {
        return "Ini halaman form biodata dan TTD untuk Pendaftar.";
    })->name('biodata');
});

// 2. Route Khusus Role: Peserta (Lolos)
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', function() {
        return "Ini halaman dashboard Peserta.";
    })->name('dashboard');
});

// 3. Route Khusus Role: Inspel
Route::middleware(['auth', 'role:inspel'])->prefix('inspel')->name('inspel.')->group(function () {
    Route::get('/dashboard', function() {
        return "Ini halaman dashboard Inspel.";
    })->name('dashboard');
});

// 4. Route Khusus Role: Pendamping
Route::middleware(['auth', 'role:pendamping'])->prefix('pendamping')->name('pendamping.')->group(function () {
    Route::get('/dashboard', function() {
        return "Ini halaman dashboard Pendamping.";
    })->name('dashboard');
});

// 5. Route Khusus Role: Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Manajemen Pendaftar
    Route::get('/pendaftar', [AdminController::class, 'pendaftarIndex'])->name('pendaftar.index');
    Route::get('/pendaftar/{id}', [AdminController::class, 'pendaftarShow'])->name('pendaftar.show');
    Route::post('/pendaftar/{id}/verifikasi', [AdminController::class, 'verifikasi'])->name('pendaftar.verifikasi');

    // Manajemen Materi
    Route::resource('materi', MateriController::class);
    Route::resource('user', UserController::class);
    Route::resource('pemateri', PemateriController::class);
    Route::resource('kelompok', KelompokController::class);

    // Manajemen Jadwal
    Route::get('/jadwal', [App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('jadwal.destroy');
});

// ==========================================

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';