<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\SiswaModel;
use App\Models\TuModel;
use App\Services\PrioritasRoleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    //
    public function index(PrioritasRoleService $prioritasRoleService)
    {
        $user = Auth()->user();
        $role = $prioritasRoleService->roleUtama(session('role', $user->role->pluck('nama')->toArray()));

        // Gunakan relasi yang sudah ada di User model
        $profileRelation = match (true) {
            in_array($role, ['walas', 'kaprog']) => 'guru',
            $role === 'siswa' => 'siswa',
            $role === 'tu' => 'tu',
            $role === 'hubin' => 'hubin',
            default => null
        };

        try {
            $profileData = $profileRelation
                ? $user->{$profileRelation}
                : abort(404, 'Profil tidak ditemukan');

            return view('dashboard.profil.index', [
                'data' => $profileData,
                'user' => $user,
            ]);
        } catch (ModelNotFoundException $e) {
            abort(404, 'Data profil tidak ditemukan');
        }
    }

    public function edit(Request $request, PrioritasRoleService $prioritasRoleService)
    {
        $user = Auth()->user();
        $role = $prioritasRoleService->roleUtama(session('role', $user->role->pluck('nama')->toArray()));

        $data = $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|regex:/^[a-z0-9\_.]+$/|unique:akun,username,' . $user->id,
            'email' => 'required|email|unique:akun,email,' . $user->id,
            'no_telp' => 'required|string|regex:/^[0-9\+]+$/',
            'jenis_kelamin' => 'required|in:L,P',
            'nip' => $user->role == 'hubin' ? 'required|digits:18|unique:hubin,nip,' . $user->id . ',id_akun' : 'prohibited', // Validasi NIP hanya untuk hubin
        ], [
            'username.regex' => 'Format username harus berupa huruf kecil, angka, dan simbol (_.).',
        ]);

        $model = match ($role) {
            ['walas', 'kaprog'] => GuruModel::where('id_akun', $user->id)->firstOrFail(),
            'siswa' => SiswaModel::where('id_akun', $user->id)->firstOrFail(),
            'hubin' => HubinModel::where('id_akun', $user->id)->firstOrFail(),
        };

        try {
            DB::transaction(function () use ($model, $data) {
                $model->akun->update($data);
                $model->update($data);
            });

            return redirect()->route('profil.index')->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->route('profil.index')->with('error', 'Profil gagal diperbarui.')->withInput();
        }
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_sekarang' => 'required|string|min:8',
            'password_baru' => 'required|string|min:8|different:password_sekarang',
            'konfirmasi_password' => 'required|string|min:8|same:password_baru',
        ]);

        $user = Auth()->user();

        // Verifikasi password saat ini
        if (!Hash::check($request->password_sekarang, $user->password)) {
            return response()->json([
                'message' => 'Password sekarang salah',
            ], 400);
        }

        // Update password
        $user->update([
            'password' => $request->konfirmasi_password,
        ]);


        return response()->json([
            'message' => 'Password berhasil diperbarui',
        ], 200);
    }
}
