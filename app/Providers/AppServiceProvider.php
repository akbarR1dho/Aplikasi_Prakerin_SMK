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
            $jenis_kelamin = 'L';
            $nama = 'guest';
            $role = 'guest';

            // Cek apakah user terautentikasi
            if ($user) {
                $modelMap = [
                    'guru' => GuruModel::class,
                    'hubin' => HubinModel::class,
                    'siswa' => SiswaModel::class
                ];

                $profile = $modelMap[$user->role]::where('id_akun', $user->id)
                    ->select('nama', 'jenis_kelamin')
                    ->first();

                $nama = $profile->nama ?? null;
                $jenis_kelamin = $profile->jenis_kelamin ?? null;

                $view->with([
                    'nama' => $nama,
                    'role' => $user->role,
                    'jenis_kelamin' => $jenis_kelamin,
                    'user' => $user,
                ]);
                return;
            }

            $view->with([
                'nama' => $nama,
                'role' => $role,
                'jenis_kelamin' => $jenis_kelamin,
            ]);
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
