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
    Route::get('/dashboard', [App\Http\Controllers\Peserta\PesertaController::class, 'dashboard'])->name('dashboard');
    Route::get('/absensi', [App\Http\Controllers\Peserta\PesertaController::class, 'absensi'])->name('absensi');
    Route::post('/absensi/tap', [App\Http\Controllers\Peserta\PesertaController::class, 'tapAbsen'])->name('absensi.tap');
    Route::get('/nilai-pemateri', [App\Http\Controllers\Peserta\PesertaController::class, 'nilaiPemateriIndex'])->name('nilai-pemateri');
    Route::post('/nilai-pemateri/store', [App\Http\Controllers\Peserta\PesertaController::class, 'nilaiPemateriStore'])->name('nilai-pemateri.store');
    Route::get('/nilai-inspel', [App\Http\Controllers\Peserta\PesertaController::class, 'nilaiInspelIndex'])->name('nilai-inspel');
    Route::post('/nilai-inspel/store', [App\Http\Controllers\Peserta\PesertaController::class, 'nilaiInspelStore'])->name('nilai-inspel.store');
    Route::get('/refleksi', [App\Http\Controllers\Peserta\PesertaController::class, 'refleksiIndex'])->name('refleksi');
    Route::post('/refleksi/store', [App\Http\Controllers\Peserta\PesertaController::class, 'refleksiStore'])->name('refleksi.store');
    Route::get('/ujian', [App\Http\Controllers\Peserta\PesertaController::class, 'ujianIndex'])->name('ujian');
    Route::get('/ujian/{materi_id}', [App\Http\Controllers\Peserta\PesertaController::class, 'ujianMulai'])->name('ujian.mulai');
    Route::post('/ujian/{materi_id}/store', [App\Http\Controllers\Peserta\PesertaController::class, 'ujianStore'])->name('ujian.store');
});

// 3. Route Khusus Role: Inspel
Route::middleware(['auth', 'role:inspel'])->prefix('inspel')->name('inspel.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Inspel\InspelController::class, 'dashboard'])->name('dashboard');
    Route::get('/pemateri', [App\Http\Controllers\Inspel\InspelController::class, 'pemateriIndex'])->name('pemateri');
    Route::get('/pemateri/{id}', [App\Http\Controllers\Inspel\InspelController::class, 'pemateriShow'])->name('pemateri.show');
    Route::get('/absensi', [App\Http\Controllers\Inspel\InspelController::class, 'absensi'])->name('absensi');
    Route::get('/penilaian', [App\Http\Controllers\Inspel\InspelController::class, 'penilaianIndex'])->name('penilaian');
    Route::get('/penilaian/create', [App\Http\Controllers\Inspel\InspelController::class, 'penilaianCreate'])->name('penilaian.create');
    Route::post('/penilaian/store', [App\Http\Controllers\Inspel\InspelController::class, 'penilaianStore'])->name('penilaian.store');
    Route::get('/refleksi', [App\Http\Controllers\Inspel\InspelController::class, 'refleksiIndex'])->name('refleksi');
    Route::get('/refleksi/{id}', [App\Http\Controllers\Inspel\InspelController::class, 'refleksiShow'])->name('refleksi.show');
});

// 4. Route Khusus Role: Pendamping
Route::middleware(['auth', 'role:pendamping'])->prefix('pendamping')->name('pendamping.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Pendamping\PendampingController::class, 'dashboard'])->name('dashboard');
    Route::get('/absensi', [App\Http\Controllers\Pendamping\PendampingController::class, 'absensi'])->name('absensi');
    Route::get('/observasi', [App\Http\Controllers\Pendamping\PendampingController::class, 'observasiIndex'])->name('observasi');
    Route::post('/observasi/store', [App\Http\Controllers\Pendamping\PendampingController::class, 'observasiStore'])->name('observasi.store');
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