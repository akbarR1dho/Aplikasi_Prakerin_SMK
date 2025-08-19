<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'role' => App\Http\Middleware\CheckRole::class,
            'auth' => App\Http\Middleware\Autentikasi::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->view('errors.404-page-view', [], 404);
        });

        // Tangani View Not Found (biasanya InvalidArgumentException)
        $exceptions->render(function (InvalidArgumentException $e) {
            if (str_contains($e->getMessage(), 'View')) {
                return response()->view('errors.404-page-view', [], 404);
            }
        });
    })->withSchedule(function (Schedule $schedule) {
        // Hapus session setiap hari pukul 02:30
        $schedule->call(function () {
            DB::table('sessions')->truncate();
        })
            ->timezone('Asia/Jakarta')
            ->dailyAt('02:30');
    })->create();
