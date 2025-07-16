<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('login-form');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|min:6',
        ], [
            'password.min' => 'Password minimal 6 karakter',
        ]);

        $tipeLogin = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $kredensial = [
            $tipeLogin => $request->login,
            'password' => $request->password
        ];

        if (Auth::attempt($kredensial)) {
            return redirect()->route('home')->with('success', 'Login berhasil');
        }

        return redirect()->route('login')->with('error', 'Username/Email atau password salah')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Logout user
        $request->session()->invalidate(); // Hapus sesi
        $request->session()->regenerateToken(); // Regenerasi token CSRF

        return response()->json(['message' => 'Logout berhasil'], 200);
    }
}
