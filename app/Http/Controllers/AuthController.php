<?php

namespace App\Http\Controllers;

use App\Services\PrioritasRoleService;
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
            'login' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Jika mengandung @, validasi sebagai email
                    if (str_contains($value, '@')) {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('Format email tidak valid');
                        }
                    }
                    // Jika tidak mengandung @, validasi sebagai username
                    else {
                        if (!preg_match('/^[a-z0-9\_.]+$/', $value)) {
                            $fail('Username hanya boleh berisi huruf kecil, angka, titik dan underscore');
                        }
                    }
                }
            ],
            'password' => 'required|min:6',
        ]);

        $tipeLogin = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $kredensial = [
            $tipeLogin => $request->login,
            'password' => $request->password
        ];

        $auth = Auth::attempt($kredensial);

        if ($auth) {
            $request->session()->regenerate();

            // Menyimpan role ke dalam session
            $user = Auth::user()->load('role');
            $role = $user->role->pluck('nama')->toArray();
            session([
                'role' => $role,
            ]);
            return redirect()->route('home')->with(['success' => 'Login berhasil', 'welcome' => 'Selamat datang kembali, ']);
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
