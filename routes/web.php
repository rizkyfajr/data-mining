<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MesinJahitController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\LaporanKinerjaController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::resource('mesinjahit', MesinJahitController::class)->except(['edit', 'update']);
   // routes/web.php

    Route::get('/mesinjahit/{mesinJahit}/edit', [MesinJahitController::class, 'edit'])->name('mesinjahit.edit');
    Route::put('/mesinjahit/{mesinJahit}', [MesinJahitController::class, 'update'])->name('mesinjahit.update');

    Route::resource('users', UserController::class);
    Route::resource('karyawan', KaryawanController::class);
});

Route::middleware(['auth', IsManager::class])->group(function () {
    Route::resource('laporan-kinerja', LaporanKinerjaController::class);
    Route::get('/laporan-kinerja/download', [LaporanKinerjaController::class, 'download'])->name('laporan-kinerja.download');
});


Route::resource('produksi', ProduksiController::class);
Route::resource('produk', ProdukController::class);

Route::get('/checkkar/karyawan-cocok', [ProduksiController::class, 'getKaryawanCocok'])->name('check.karyawan-cocok');
Route::get('/produksi/estimasi-waktu', [ProduksiController::class, 'estimasiWaktu']);




Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

// Route::get('/laporan-kinerja', [LaporanKinerjaController::class, 'index'])->name('laporan-kinerja.index');


require __DIR__.'/auth.php';
