<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login-form');
})->middleware('guest')->name('login');

// Auth Routes
Route::post('/login', [AuthController::class, 'login'])->name('login-post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/home', [DashboardController::class, 'home'])->name('home')->middleware('auth');

Route::prefix('akun-guru')->middleware('role:hubin')->group(function () {
    Route::get('/', [DashboardController::class, 'akunGuru'])->name('akun-guru.index');
    Route::get('/tambah', [DashboardController::class, 'showTambahAkunGuru'])->name('akun-guru.form-tambah');
    Route::post('/tambah', [AkunController::class, 'tambahAkunGuru'])->name('akun-guru.tambah');
    Route::delete('/{id}', [AkunController::class, 'deleteAkunGuru'])->name('akun-guru.delete');
});
