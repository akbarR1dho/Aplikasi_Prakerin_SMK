<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function home()
    {
        // $user = Auth()->user();
        // $data_session = DB::table('sessions')->where('user_id', $user->id)->first();
        // $lastActivity = $data_session->last_activity; // Timestamp terakhir
        // $lifetime = Config::get('session.lifetime') * 60; // Dalam detik
        // $expireTime = $lastActivity + $lifetime;
        // $remaining = $lifetime;

        return view('dashboard.home');
    }

    public function downloadTemplate($nama_file)
    {
        if (file_exists(public_path('template_files/' . $nama_file))) {
            return response()->download(public_path('template_files/' . $nama_file));
        }
        return redirect()->back()->with('error', 'File template tidak ditemukan');
    }
}
