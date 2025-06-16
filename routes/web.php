<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PengaturanController;
use Illuminate\Support\Facades\Route;


Route::get('/home', [DashboardController::class, 'home'])->name('home')->middleware('auth');
Route::redirect('/', '/home'); // Mengubah default route ke /home

// Auth Routes
Route::get('/login', [AuthController::class, 'index'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index')->middleware('role:hubin');
Route::post('/pengaturan/update', [PengaturanController::class, 'update'])->name('pengaturan.update')->middleware('role:hubin');

// Route Guru
Route::prefix('akun-guru')->middleware('role:hubin')->group(function () {
    Route::get('/', [GuruController::class, 'index'])->name('akun-guru.index');
    Route::get('/detail/{id}', [GuruController::class, 'detail'])->name('akun-guru.detail');
    Route::get('/tambah', [GuruController::class, 'formTambah'])->name('akun-guru.form-tambah');
    Route::post('/tambah', [GuruController::class, 'tambah'])->name('akun-guru.tambah');
    Route::get('/import', [GuruController::class, 'formImport'])->name('akun-guru.form-import');
    Route::post('/import', [GuruController::class, 'import'])->name('akun-guru.import');
    Route::get('/edit/{id}', [GuruController::class, 'formEdit'])->name('akun-guru.form-edit');
    Route::put('/edit/{id}', [GuruController::class, 'edit'])->name('akun-guru.edit');
    Route::post('/reset-password/{id}', [GuruController::class, 'resetPassword'])->name('akun-guru.reset-password');
    Route::delete('/hapus/{id}', [GuruController::class, 'hapus'])->name('akun-guru.hapus');
});

// Route Jurusan
Route::prefix('jurusan')->middleware('role:hubin')->group(function () {
    Route::get('/', [JurusanController::class, 'index'])->name('jurusan.index');
    Route::get('/load-kaprog', [JurusanController::class, 'loadKaprog'])->name('jurusan.load-kaprog');
    Route::get('/get-data/{id}', [JurusanController::class, 'getData'])->name('jurusan.get-data');
    Route::post('/simpan', [JurusanController::class, 'simpan'])->name('jurusan.simpan');
    Route::delete('/{id}', [JurusanController::class, 'hapus'])->name('jurusan.hapus');
});

// Route Kelas
Route::prefix('kelas')->middleware('role:hubin')->group(function () {
    Route::get('/', [KelasController::class, 'index'])->name('kelas.index');
    Route::get('/tambah', [KelasController::class, 'formTambah'])->name('kelas.form-tambah');
    Route::post('/tambah-data', [KelasController::class, 'tambah'])->name('kelas.tambah');
    Route::get('/load-jurusan', [KelasController::class, 'loadJurusan'])->name('kelas.load-jurusan');
    Route::get('/load-walas', [KelasController::class, 'loadWalas'])->name('kelas.load-walas');
    Route::get('/detail/{id}', [KelasController::class, 'detail'])->name('kelas.detail');
    Route::get('/data-walas/{id}', [KelasController::class, 'dataWalas'])->name('kelas.data-walas');
    Route::put('/ganti-walas', [KelasController::class, 'gantiWalas'])->name('kelas.ganti-walas');
    Route::delete('/{id}', [KelasController::class, 'hapus'])->name('kelas.hapus');
});
