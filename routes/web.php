<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;


Route::get('/home', [DashboardController::class, 'home'])->name('home')->middleware('auth');
Route::redirect('/', '/home'); // Mengubah default route ke /home

// Auth Routes
Route::get('/login', [AuthController::class, 'index'])->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index')->middleware('role:hubin');
Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update')->middleware('role:hubin');
