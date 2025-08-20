<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemasukanProyekController;
use App\Http\Controllers\PengeluaranProyekController;


// Login routes (di luar auth)
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// Jika user mengakses /login via GET, redirect otomatis
Route::get('/login', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/');
});

// Proses login via POST
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Route yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Akun
    Route::resource('users', UserController::class);

    // Master Data
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/data', [ProjectController::class, 'getData'])->name('proyek.data');

    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');


    //vendor 
    Route::resource('vendor', VendorController::class);
    Route::get('/vendor/{vendor}', [VendorController::class, 'show'])->name('vendor.show');
    Route::resource('vendors', VendorController::class)->only('index');



    // Monitoring pemasukan
    Route::resource('pemasukan', PemasukanProyekController::class);
    Route::get('/pemasukan/{id}/bukti/{termin}', [PemasukanProyekController::class, 'lihatBukti'])
        ->name('pemasukan.bukti');
    Route::get('/pemasukan/{id_proyek}/detail', [PemasukanProyekController::class, 'detail'])->name('pemasukan.detail');
    Route::post('pemasukan/{id_proyek}/update-lunasi/{termin_ke}', [PemasukanProyekController::class, 'updateLunasi'])->name('pemasukan.updateLunasi');
    Route::get('/api/termin-status/{id}/{termin}', [PemasukanProyekController::class, 'getTerminStatus']);
    Route::get('/api/termin-detail/{id}', [PemasukanProyekController::class, 'getTerminDetail']);
    Route::post('/pemasukan/{id_proyek}/lunasi/{termin_ke}', [PemasukanProyekController::class, 'storeLunasi'])->name('pemasukan.storeLunasi');



    // Monitoring Pengeluaran
    Route::resource('pengeluaran', PengeluaranProyekController::class);
    Route::post('pengeluaran/{id}/approve', [PengeluaranProyekController::class, 'approve'])->name('pengeluaran.approve');
    Route::post('pengeluaran/{id}/reject', [PengeluaranProyekController::class, 'reject'])->name('pengeluaran.reject');


    Route::get('/pengeluaran/create', [PengeluaranProyekController::class, 'create'])->name('pengeluaran.create');
    Route::get('/vendor-by-jenis/{jenis}', [PengeluaranProyekController::class, 'getVendorByJenis'])->name('vendor.by.jenis');
    Route::post('/pengeluaran/{id_pengeluaran}/prosesPengeluaran', [PengeluaranProyekController::class, 'prosesPengeluaran'])->name('pengeluaran.prosesPengeluaran');


    //Laporan
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

// require __DIR__ . '/auth.php';
