<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    //
    public function home()
    {
        return view('dashboard.home');
    }
}
