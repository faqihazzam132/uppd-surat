<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

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

    // Login Admin menggunakan form & logic yang sama
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login']);

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

    // Notifikasi (untuk semua user yang login)
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
    });

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

        // Verifikasi & Laporan Pengajuan Online
        Route::get('/admin/pengajuan', [PengajuanController::class, 'indexAdmin'])->name('admin.pengajuan.index');
        Route::get('/admin/pengajuan/export/pdf', [PengajuanController::class, 'exportPdf'])->name('admin.pengajuan.export.pdf');
        Route::patch('/admin/pengajuan/{id}', [PengajuanController::class, 'verify'])->name('admin.pengajuan.verify');
        Route::get('/admin/pengajuan/{id}/file', [PengajuanController::class, 'viewFileAdmin'])->name('admin.pengajuan.file');

        // Arsip
        Route::resource('arsip', \App\Http\Controllers\ArsipController::class);
        Route::get('/arsip/create/{type}/{id}', [\App\Http\Controllers\ArsipController::class, 'create'])->name('arsip.create_from_surat');
    });

    // --- KHUSUS ADMIN: Manajemen Pengguna ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/users/{user}/reset-password', [AdminUserController::class, 'showResetForm'])->name('users.reset-password');
        Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password.update');
    });

    // --- KHUSUS PEMOHON (Masyarakat) ---
    Route::middleware(['role:pemohon'])->group(function () {
        // PASTIKAN NAMA ROUTE-NYA 'pengajuan.index'
        Route::get('/pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
        
        Route::get('/pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{id}', [PengajuanController::class, 'show'])->name('pengajuan.show');
        Route::get('/pengajuan/{id}/file', [PengajuanController::class, 'viewFile'])->name('pengajuan.file');
        Route::get('/pengajuan/{id}/bukti', [PengajuanController::class, 'downloadReceipt'])->name('pengajuan.bukti');
    });
});