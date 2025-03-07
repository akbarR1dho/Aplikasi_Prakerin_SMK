<?php

namespace App\Providers;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\SiswaModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer(['dashboard.home', 'layouts.dashboard'], function ($view) {
            $user = auth()->user();
            $nama = 'guest';

            // Cek apakah user ada di tabel guru, hubin, atau siswa
            if ($guru = GuruModel::where('id_akun', $user->id)->select('nama')->first()) {
                $nama = $guru->nama;
            } elseif ($hubin = HubinModel::where('id_akun', $user->id)->select('nama')->first()) {
                $nama = $hubin->nama;
            } elseif ($siswa = SiswaModel::where('id_akun', $user->id)->first()) {
                $nama = $siswa->nama;
            } else {
                $nama = 'Pengguna Tidak Diketahui';
            }
            $view->with('user', $user)->with('nama', $nama);
        });
    }
}
