<?php

namespace App\Providers;

use App\Services\NormalisasiNamaService;
use Illuminate\Support\ServiceProvider;

class NormalisasiNamaProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        //
        $this->app->singleton(NormalisasiNamaService::class, fn() => new NormalisasiNamaService());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
