<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/home'); // Mengubah default route ke /login
Route::get('/home', [DashboardController::class, 'home'])->name('home')->middleware('role');
Route::get('/download-template/{nama_file}', [DashboardController::class, 'downloadTemplate'])->name('download-template')->middleware('role');

// Route Autentikasi
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'index')->middleware('guest');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

// Route Kelola Pengaturan
Route::prefix('pengaturan')->controller(PengaturanController::class)->middleware('role:hubin')->group(function () {
    Route::get('/', 'index')->name('pengaturan.index');
    Route::put('/edit', 'edit')->name('pengaturan.edit');
});

// Route Kelola Profil
Route::prefix('profil')->controller(ProfilController::class)->middleware('role')->group(function () {
    Route::get('/', 'index')->name('profil.index');
    Route::put('/edit', 'edit')->name('profil.edit');
    Route::put('/ubah-password', 'ubahPassword')->name('profil.ubah-password');
});

// Route Kelola Akun Guru
Route::prefix('akun-guru')->controller(GuruController::class)->middleware('role:hubin')->group(function () {
    Route::get('/', 'index')->name('akun-guru.index');
    Route::get('/detail/{id}', 'detail')->name('akun-guru.detail');
    Route::get('/tambah', 'formTambah')->name('akun-guru.form-tambah');
    Route::post('/tambah', 'tambah')->name('akun-guru.tambah');
    Route::get('/import', 'formImport')->name('akun-guru.form-import');
    Route::post('/import', 'import')->name('akun-guru.import');
    Route::get('/edit/{id}', 'formEdit')->name('akun-guru.form-edit');
    Route::put('/edit/{id}', 'edit')->name('akun-guru.edit');
    Route::post('/reset-password/{id}', 'resetPassword')->name('akun-guru.reset-password');
    Route::delete('/hapus/{id}', 'hapus')->name('akun-guru.hapus');
});

// Route Kelola Jurusan
Route::prefix('jurusan')->controller(JurusanController::class)->middleware('role:hubin')->group(function () {
    Route::get('/', 'index')->name('jurusan.index');
    Route::get('/load-kaprog', 'loadKaprog')->name('jurusan.load-kaprog');
    Route::get('/get-data/{id}', 'getData')->name('jurusan.get-data');
    Route::post('/simpan', 'simpan')->name('jurusan.simpan');
    Route::delete('/hapus/{id}', 'hapus')->name('jurusan.hapus');
});

// Route Kelola Kelas
Route::prefix('kelas')->controller(KelasController::class)->middleware('role:hubin')->group(function () {
    Route::get('/', 'index')->name('kelas.index');
    Route::get('/tambah', 'formTambah')->name('kelas.form-tambah');
    Route::post('/tambah-data', 'tambah')->name('kelas.tambah');
    Route::get('/load-jurusan', 'loadJurusan')->name('kelas.load-jurusan');
    Route::get('/load-walas', 'loadWalas')->name('kelas.load-walas');
    Route::get('/detail/{id}', 'detail')->name('kelas.detail');
    Route::get('/data-siswa/{id}', 'dataSiswa')->name('kelas.data-siswa');
    Route::get('/data-walas/{id}', 'dataWalas')->name('kelas.data-walas');
    Route::put('/ganti-walas', 'gantiWalas')->name('kelas.ganti-walas');
    Route::delete('/hapus/{id}', 'hapus')->name('kelas.hapus');
});

// Route Kelola Akun Siswa
Route::prefix('akun-siswa')->controller(SiswaController::class)->middleware('role:hubin')->group(function () {
    Route::get('/', 'index')->name('akun-siswa.index');
    Route::get('/detail/{nis}', 'detail')->name('akun-siswa.detail');
    Route::get('/tambah', 'formTambah')->name('akun-siswa.form-tambah');
    Route::post('/tambah', 'tambah')->name('akun-siswa.tambah');
    Route::get('/load-kelas', 'loadKelas')->name('akun-siswa.load-kelas');
    Route::get('/import', 'formImport')->name('akun-siswa.form-import');
    Route::post('/import', 'import')->name('akun-siswa.import');
    Route::get('/edit/{nis}', 'formEdit')->name('akun-siswa.form-edit');
    Route::put('/edit/{nis}', 'edit')->name('akun-siswa.edit');
    Route::post('/reset-password/{nis}', 'resetPassword')->name('akun-siswa.reset-password');
    Route::delete('/hapus/{nis}', 'hapus')->name('akun-siswa.hapus');
});
