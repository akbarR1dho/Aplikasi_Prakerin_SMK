<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\JurusanModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use Illuminate\Auth\Access\AuthorizationException;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Periksa apakah user sudah login
        if (!$user) {
            Auth::logout();
            $request->session()->invalidate(); // Hapus sesi
            $request->session()->regenerateToken(); // Regenerasi token CSRF

            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Jika role user tidak ada dalam daftar yang diizinkan, blokir akses
        if (!in_array($user->role, $roles) && !empty($roles)) {
            return abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        if ($user->role === 'guru') {
            // Cek apakah user ada di tabel guru
            $guru = GuruModel::with('roles')->where('id_akun', $user->id)->select('id')->first();

            $cekRole = [
                'walas' => $guru->roles->contains('nama', 'guru') || KelasModel::where('id_walas', $guru->id)->exists(),
                'kaprog' => $guru->roles->contains('nama', 'kaprog') || JurusanModel::where('id_kaprog', $guru->id)->exists(),
                'pembimbing' => $guru->roles->contains('nama', 'pembimbing')
            ];

            foreach ($roles as $role) {
                if (isset($cekRole[$role]) && !$cekRole[$role]) {
                    throw new AuthorizationException('Anda tidak memiliki akses sebagai ' . $role);
                }
            }
        }

        return $next($request);
    }
}
