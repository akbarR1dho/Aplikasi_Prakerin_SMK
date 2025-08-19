<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('logs:clear', function () {
    if ($this->confirm('Apakah anda yakin?')) {
        exec('echo "" > ' . storage_path('logs/laravel.log'));
        $this->info('Log berhasil dihapus');
    }
})->purpose('Menghapus log');

Artisan::command('sessions:clear', function () {
    if ($this->confirm('Apakah anda yakin?')) {
        DB::table('sessions')->truncate();
        $this->info('Sessions berhasil dihapus');
    }
})->purpose('Menghapus sessions');
