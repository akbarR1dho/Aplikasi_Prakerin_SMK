<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return view('login-form');
})->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])->name('login-post');

Route::get('/dashboard-hubin', function () {
    return view('dashboard-hubin');
})->name('dashboard-hubin')->middleware('role:hubin');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
