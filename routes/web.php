<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KategoriController;

// ================= PAYMENT =================
Route::post('/midtrans/callback', [PaymentController::class, 'callback']);
Route::get('/payment/success', [PaymentController::class, 'success']);

// ================= ROOT =================
Route::get('/', fn() => redirect()->route('login'));

// ================= AUTH =================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', function () {
    return redirect()->route('login');
});

// ================= PASSWORD =================
Route::prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/password/change', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'changePassword'])->name('password.change.proses');
});

// ================= DASHBOARD REDIRECT =================
Route::get('/dashboard', function () {
    if (auth()->guard('superadmin')->check()) {
        return redirect()->route('superadmin.dashboard');
    }
    if (auth()->guard('web')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('dashboard');

// ================= SUPERADMIN =================
Route::middleware(['auth:superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengaturan', [DashboardController::class, 'superadminPengaturan'])->name('pengaturan');
        Route::get('/transaksi', [DashboardController::class, 'transaksi'])->name('transaksi');

        Route::post('/update-profile', [DashboardController::class, 'updateProfile'])->name('updateProfile');
        Route::post('/update-password', [DashboardController::class, 'updatePassword'])->name('updatePassword');
        Route::post('/update-akses', [DashboardController::class, 'updateAkses'])->name('updateAkses');

        Route::resource('users', UserController::class)->except(['show']); // ✅ Hanya satu ini
        Route::resource('rekomendasi', RekomendasiController::class);
        Route::resource('destinasi', DestinasiController::class);

        Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
        Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/rekap-transaksi', [TransaksiController::class, 'rekap'])->name('rekap.transaksi');
        Route::get('/rekap-transaksi/pdf', [TransaksiController::class, 'rekapPDF'])->name('rekap.transaksi.pdf');

    }); // ✅ Tutup superadmin group

// ================= ADMIN =================
Route::middleware(['auth:web'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/pengaturan', [DashboardController::class, 'adminPengaturan'])->name('pengaturan');

        Route::post('/pengaturan/update', [DashboardController::class, 'updateProfile'])->name('updateProfile');
        Route::post('/pengaturan/password', [DashboardController::class, 'updatePassword'])->name('updatePassword');

        Route::resource('destinasi', DestinasiController::class);

        Route::get('/transaksi/cetak', [TransaksiController::class, 'cetakIndex'])->name('transaksi.cetak');
        Route::get('/transaksi', [DashboardController::class, 'transaksi'])->name('transaksi.index');

    }); // ✅ Tutup admin group