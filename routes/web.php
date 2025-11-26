<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\PengajuanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- HALAMAN DEPAN & AUTHENTICATION ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Auth (Sudah Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');


    // Dashboard (Otomatis beda tampilan Admin vs Pemohon di Controller)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- KHUSUS INTERNAL (Admin, Staff, Kepala Unit) ---
    Route::middleware(['role:admin,staff,kepala_unit,kasubbag'])->group(function () {
        
        // Surat Masuk
        Route::resource('surat-masuk', SuratMasukController::class);

        // Surat Keluar
        Route::resource('surat-keluar', SuratKeluarController::class);
        Route::patch('/surat-keluar/{id}/status', [SuratKeluarController::class, 'updateStatus'])->name('surat-keluar.status');
        Route::post('/surat-keluar/{id}/upload-final', [SuratKeluarController::class, 'uploadFinal'])->name('surat-keluar.upload-final');

        // Disposisi
        Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
        Route::get('/disposisi/create/{surat_id}', [DisposisiController::class, 'create'])->name('disposisi.create');
        Route::post('/disposisi', [DisposisiController::class, 'store'])->name('disposisi.store');

        // Verifikasi Pengajuan Online
        Route::get('/admin/pengajuan', [PengajuanController::class, 'indexAdmin'])->name('admin.pengajuan.index');
        Route::patch('/admin/pengajuan/{id}', [PengajuanController::class, 'verify'])->name('admin.pengajuan.verify');
    });

    // --- KHUSUS PEMOHON (Masyarakat) ---
    Route::middleware(['role:pemohon'])->group(function () {
        // PASTIKAN NAMA ROUTE-NYA 'pengajuan.index'
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        
        Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    });
});