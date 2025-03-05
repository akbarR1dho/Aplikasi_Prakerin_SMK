<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\GuruModel;
use App\Models\HubinModel;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        if (!$user) {
            return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Jika role user tidak ada dalam daftar yang diizinkan, blokir akses
        if (!in_array($user->role, $roles)) {
            return abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Periksa role user
        switch ($user->role) {
            case 'hubin':
                // Cek apakah user ada di tabel hubin
                if (!HubinModel::where('id_akun', $user->id)->exists()) {
                    return redirect('/login')->with('error', 'Akun hubin tidak ditemukan.');
                }
                break;

            case 'guru':
                // Cek apakah user ada di tabel guru
                $guru = GuruModel::where('id_akun', $user->id)->first();
                if (!$guru) {
                    return redirect('/')->with('error', 'Akun guru tidak ditemukan.');
                }

                // Jika role tambahan diperlukan (walas/kaprog/pembimbing)
                if (in_array('walas', $roles) && !$guru->roles->contains('nama_role', 'walas')) {
                    return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai Walas.');
                }
                if (in_array('kaprog', $roles) && !$guru->roles->contains('nama_role', 'kaprog')) {
                    return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai Kaprog.');
                }
                if (in_array('pembimbing', $roles) && !$guru->roles->contains('nama_role', 'pembimbing')) {
                    return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai Pembimbing.');
                }
                break;

            // case 'siswa':
            //     // Cek apakah user ada di tabel siswa
            //     if (!Siswa::where('user_id', $user->id)->exists()) {
            //         return redirect('/')->with('error', 'Anda tidak memiliki akses sebagai Siswa.');
            //     }
            //     break;

            default:
                return redirect('/login')->with('error', 'Role tidak valid.');
        }

        return $next($request);
    }
}
