<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    //
    public function home()
    {
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
