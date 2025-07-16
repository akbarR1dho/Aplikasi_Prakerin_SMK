<?php

namespace App\Providers;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\PengaturanModel;
use App\Models\SiswaModel;
use App\View\Components\FlashMessage;
use Illuminate\Support\Facades\Blade;
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
            if ($user->role == 'guru') {
                $guru = GuruModel::where('id_akun', $user->id)->select('nama', 'jenis_kelamin')->first();
                $nama = $guru->nama;
                $jenis_kelamin = $guru->jenis_kelamin;
            } elseif ($user->role == 'hubin') {
                $hubin = HubinModel::where('id_akun', $user->id)->select('nama', 'jenis_kelamin')->first();
                $nama = $hubin->nama;
                $jenis_kelamin = $hubin->jenis_kelamin;
            } elseif ($user->role == 'siswa') {
                $siswa = SiswaModel::where('id_akun', $user->id)->select('nama', 'jenis_kelamin')->first();
                $nama = $siswa->nama;
                $jenis_kelamin = $siswa->jenis_kelamin;
            }

            $view->with('user', $user)->with('nama', $nama)->with('jenis_kelamin', $jenis_kelamin);
        });

        Blade::component('flash-message', FlashMessage::class);

        $pengaturan = [
            'app_name' => PengaturanModel::get('app_name'),
            'app_icon' => PengaturanModel::get('app_icon'),
            'app_default_password' => PengaturanModel::get('app_default_password'),
        ];

        View::share('pengaturan', $pengaturan);
    }
}
