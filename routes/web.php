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
use App\Http\Controllers\AddendumController;



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

    Route::post('projects/{id_proyek}/addendum/store', [ProjectController::class, 'addendumStore'])
        ->name('projects.addendum.store');



    Route::get('projects/{idProyek}/addendums', [AddendumController::class, 'index']);
    Route::post('projects/{idProyek}/addendums', [AddendumController::class, 'store']);
    Route::delete('/projects/{project}/addendums/{addendum}', [AddendumController::class, 'destroy'])
        ->name('addendums.destroy');




    //vendor 
    Route::get('vendors/datatable', [VendorController::class, 'datatable'])->name('vendors.datatable');
    Route::resource('vendors', VendorController::class);


    // Monitoring pemasukan
    Route::get('pemasukan/datatable', [PemasukanProyekController::class, 'datatable'])->name('pemasukan.datatable');
    Route::resource('pemasukan', PemasukanProyekController::class);
    Route::get('/pemasukan/{id}/bukti/{termin}', [PemasukanProyekController::class, 'lihatBukti'])
        ->name('pemasukan.bukti');
    Route::get('/pemasukan/{id_proyek}/detail', [PemasukanProyekController::class, 'detail'])->name('pemasukan.detail');
    Route::post('pemasukan/{id_proyek}/update-lunasi/{termin_ke}', [PemasukanProyekController::class, 'updateLunasi'])->name('pemasukan.updateLunasi');
    Route::get('/api/termin-status/{id}/{termin}', [PemasukanProyekController::class, 'getTerminStatus']);
    Route::get('/api/termin-detail/{id}', [PemasukanProyekController::class, 'getTerminDetail']);
    Route::post('/pemasukan/{id_proyek}/lunasi/{termin_ke}', [PemasukanProyekController::class, 'storeLunasi'])->name('pemasukan.storeLunasi');


    // routes/web.php
    Route::get('/api/termin-proyek/{id}', [PemasukanProyekController::class, 'getDetail']);


    // Monitoring Pengeluaran
    Route::get('pengeluaran/data', [PengeluaranProyekController::class, 'getData'])->name('pengeluaran.data');
    Route::resource('pengeluaran', PengeluaranProyekController::class);

    Route::post('pengeluaran/{id}/approve', [PengeluaranProyekController::class, 'approve'])->name('pengeluaran.approve');
    Route::post('pengeluaran/{id}/reject', [PengeluaranProyekController::class, 'reject'])->name('pengeluaran.reject');
Route::patch('pengeluaran/{id}/cancel', [PengeluaranProyekController::class, 'cancel'])
    ->name('pengeluaran.cancel');


    Route::get('/pengeluaran/create', [PengeluaranProyekController::class, 'create'])->name('pengeluaran.create');
    Route::get('/vendor-by-jenis/{jenis}', [PengeluaranProyekController::class, 'getVendorByJenis'])->name('vendor.by.jenis');
    Route::post('/pengeluaran/{id_pengeluaran}/prosesPengeluaran', [PengeluaranProyekController::class, 'prosesPengeluaran'])->name('pengeluaran.prosesPengeluaran');


    //Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/rekap', [LaporanController::class, 'rekap'])->name('rekap');
        Route::get('/keuangan', [LaporanController::class, 'keuangan'])->name('keuangan');
    });



    Route::get('/storage/{path}', function ($path) {
        $file = Storage::disk('public')->path($path);

        if (!file_exists($file)) {
            abort(404);
        }

        return response()->file($file);
    })->where('path', '.*');
});

// require __DIR__ . '/auth.php';
