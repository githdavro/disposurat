<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\DirekturController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| File ini berisi semua route web aplikasi kamu.
| Arahkan route utama ke halaman login agar tidak menampilkan
| halaman default "Laravel".
|
*/

Route::get('/', function () {
    // Arahkan langsung ke halaman login
    return redirect()->route('login');
});

// Dashboard — hanya bisa diakses setelah login & verifikasi email
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Group middleware untuk semua route yang butuh autentikasi
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Notifikasi Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');

    /*
    |--------------------------------------------------------------------------
    | Unit Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('unit')->group(function () {
        Route::get('/create-surat', [SuratController::class, 'create'])->name('unit.create-surat');
        Route::post('/surat', [SuratController::class, 'store'])->name('surat.store');
        Route::get('/sent', [SuratController::class, 'sent'])->name('unit.sent');
        Route::get('/inbox', [SuratController::class, 'inbox'])->name('unit.inbox');
        Route::get('/surat/{id}', [SuratController::class, 'show'])->name('unit.detail-surat');
    });

    /*
    |--------------------------------------------------------------------------
    | Pengadaan Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('pengadaan')->group(function () {
        Route::get('/inbox', [PengadaanController::class, 'inbox'])->name('pengadaan.inbox');
        Route::get('/surat/{id}', [PengadaanController::class, 'detailSurat'])->name('pengadaan.detail-surat');
        Route::post('/distribusi/{id}', [PengadaanController::class, 'distribusi'])->name('pengadaan.distribusi');
    });

    /*
    |--------------------------------------------------------------------------
    | Direktur Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('direktur')->group(function () {
        Route::get('/review', [DirekturController::class, 'review'])->name('direktur.review');
        Route::get('/disposisi/{id}', [DirekturController::class, 'disposisiForm'])->name('direktur.disposisi-form');
        Route::post('/disposisi/{id}', [DirekturController::class, 'prosesDisposisi'])->name('direktur.proses-disposisi');
        Route::get('/arsip', [DirekturController::class, 'arsip'])->name('direktur.arsip');
    });

    /*
    |--------------------------------------------------------------------------
    | Disposisi Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/disposisi/{id}/terima', [DisposisiController::class, 'terimaDisposisi'])->name('disposisi.terima');
    Route::post('/disposisi/{id}/selesai', [DisposisiController::class, 'selesaikanDisposisi'])->name('disposisi.selesai');

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'userManagement'])->name('admin.users');
        Route::get('/units', [AdminController::class, 'unitManagement'])->name('admin.units');
        Route::get('/logs', [AdminController::class, 'systemLogs'])->name('admin.logs');
        
        // User management
        Route::post('/users', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
        
        // Unit management
        Route::post('/units', [AdminController::class, 'createUnit'])->name('admin.units.create');
        Route::delete('/units/{unit}', [AdminController::class, 'destroy'])->name('admin.units.destroy');
    }); // close admin prefix group

}); // close auth middleware group

// Auth routes (login, register, password reset, dll)
require __DIR__.'/auth.php';
