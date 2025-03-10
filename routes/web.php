<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login-form');
})->middleware('guest')->name('login');

// Auth Routes
Route::post('/login', [AuthController::class, 'login'])->name('login-post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/home', [DashboardController::class, 'home'])->name('home')->middleware('auth');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index')->middleware('role:hubin');
Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update')->middleware('role:hubin');

Route::redirect('/', '/home'); // Mengubah default route ke /home

Route::prefix('akun-guru')->middleware('role:hubin')->group(function () {
    Route::get('/', [DashboardController::class, 'akunGuru'])->name('akun-guru.index');
    Route::get('/tambah', [DashboardController::class, 'showTambahAkunGuru'])->name('akun-guru.form-tambah');
    Route::post('/tambah', [AkunController::class, 'tambahAkunGuru'])->name('akun-guru.tambah');
    Route::delete('/{id}', [AkunController::class, 'deleteAkunGuru'])->name('akun-guru.delete');
    Route::get('/edit/{id}', [DashboardController::class, 'showEditAkunGuru'])->name('akun-guru.form-edit');
    Route::put('/{id}', [AkunController::class, 'editAkunGuru'])->name('akun-guru.edit');
    Route::get('/detail/{id}', [DashboardController::class, 'detailAkunGuru'])->name('akun-guru.detail');
    Route::post('/ganti-password', [AkunController::class, 'gantiPasswordGuru'])->name('akun-guru.ganti-password');
});

Route::prefix('jurusan')->middleware('role:hubin')->group(function () {
    Route::get('/', [DashboardController::class, 'jurusan'])->name('jurusan.index');
    Route::get('/get-guru', [JurusanController::class, 'getGuru'])->name('jurusan.get-guru');
    Route::get('/get-data/{id}', [JurusanController::class, 'getData'])->name('jurusan.get-data');
    Route::post('/simpan', [JurusanController::class, 'simpan'])->name('jurusan.simpan');
    Route::delete('/{id}', [JurusanController::class, 'delete'])->name('jurusan.delete');
});
