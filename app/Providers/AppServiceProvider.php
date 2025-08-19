<?php

namespace App\Providers;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\PengaturanModel;
use App\Models\SiswaModel;
use App\Models\TuModel;
use App\Services\NormalisasiNamaService;
use App\Services\PrioritasRoleService;
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
        $this->app->singleton(NormalisasiNamaService::class, fn() => new NormalisasiNamaService());
        $this->app->singleton(PrioritasRoleService::class, fn() => new PrioritasRoleService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        View::composer(['dashboard.home', 'layouts.dashboard', 'dashboard.pengajuan.index-guru'], function ($view) {
            $user = auth()->user();
            $roleService = app(PrioritasRoleService::class);
            $defaults = [
                'nama' => 'guest',
                'jenis_kelamin' => 'L',
                'role' => 'guest',
                'roles' => ['guest'] // Default role
            ];

            if (!$user) {
                return $view->with($defaults);
            }

            // Ambil data roles dari session (jika sudah ada)
            $roles = $user ? session('role', $user->role->pluck('nama')->toArray()) : ['guest'];

            // Model mapping untuk profile
            $modelMap = [
                'tu' => TuModel::class,
                'kaprog' => GuruModel::class,
                'walas' => GuruModel::class,
                'hubin' => HubinModel::class,
                'siswa' => SiswaModel::class
            ];

            // Dapatkan primary role (role pertama atau default)
            $roleUtama = $roleService->roleUtama($roles);

            // Query profile hanya jika model terdefinisi
            $profile = isset($modelMap[$roleUtama])
                ? $modelMap[$roleUtama]::where('id_akun', $user->id)
                ->select('nama', 'jenis_kelamin')
                ->first()
                : null;

            $view->with([
                'nama' => $profile->nama,
                'jenis_kelamin' => $profile->jenis_kelamin,
                'role' => $roleUtama, // Untuk kompatibilitas backward
                'roles' => $roles,      // Semua roles dalam array
                'user' => $user,
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
