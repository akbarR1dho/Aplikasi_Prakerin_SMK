<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\SiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    //
    public function index()
    {
        $user = Auth()->user();

        $data = match ($user->role) {
            'guru' => GuruModel::where('id_akun', $user->id)->firstOrFail(),
            'siswa' => SiswaModel::where('id_akun', $user->id)->firstOrFail(),
            'hubin' => HubinModel::where('id_akun', $user->id)->firstOrFail(),
        };

        return view('dashboard.profil.index', [
            'data' => $data,
            'user' => $user
        ]);
    }

    public function edit(Request $request)
    {
        $user = Auth()->user();

        $data = $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|unique:akun,username,' . $user->id,
            'email' => 'required|email|unique:akun,email,' . $user->id,
            'no_telp' => 'required|string|regex:/^[0-9\+]+$/',
            'jenis_kelamin' => 'required|in:L,P',
            'nip' => $user->role == 'hubin' ? 'required|digits:18|unique:hubin,nip,' . $user->id . ',id_akun' : 'prohibited', // Validasi NIP hanya untuk hubin
        ]);

        $model = match ($user->role) {
            'guru' => GuruModel::where('id_akun', $user->id)->firstOrFail(),
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
}
