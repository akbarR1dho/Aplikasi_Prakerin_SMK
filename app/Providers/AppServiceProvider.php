<?php

namespace App\Providers;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\SettingsModel;
use App\Models\SiswaModel;
use App\View\Components\FlashMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
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
            if ($guru = GuruModel::where('id_akun', $user->id)->select('nama', 'jenis_kelamin')->first()) {
                $nama = $guru->nama;
                $jenis_kelamin = $guru->jenis_kelamin;
            } elseif ($hubin = HubinModel::where('id_akun', $user->id)->select('nama', 'jenis_kelamin')->first()) {
                $nama = $hubin->nama;
                $jenis_kelamin = $hubin->jenis_kelamin;
            } elseif ($siswa = SiswaModel::where('id_akun', $user->id)->select('nama', 'jenis_kelamin')->first()) {
                $nama = $siswa->nama;
                $jenis_kelamin = $siswa->jenis_kelamin;
            } else {
                $nama = 'Pengguna Tidak Diketahui';
            }
            $view->with('user', $user)->with('nama', $nama)->with('jenis_kelamin', $jenis_kelamin);
        });

        Blade::component('flash-message', FlashMessage::class);
        
        $settings = [
            'app_name' => SettingsModel::get('app_name'),
            'app_icon' => SettingsModel::get('app_icon'),
        ];

        View::share('settings', $settings);
    }
}
