<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    //
    public function tambahAkunGuru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nip' => 'required|unique:guru,nip',
            'email' => 'required|email|unique:guru,email',
            'no_telp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->route('akun-guru.tambah')->withErrors($validator)->withInput();
        }

        $akun = User::create([
            'username' => explode(' ', trim($request->nama))[0] . substr($request->nip, 0, 4),
            'password' => 'guru#1234',
            'role' => 'guru',
        ]);

        if (!$akun) {
            return redirect()->route('akun-guru.tambah')->with('error', 'Gagal membuat akun');
        }

        $guru = GuruModel::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'id_akun' => $akun->id,
        ]);

        if (!$guru) {
            return redirect()->route('akun-guru.tambah')->with('error', 'Gagal membuat data guru');
        }

        return redirect()->route('akun-guru.index')->with('success', 'Akun dan data guru berhasil dibuat');
    }

    public function deleteAkunGuru($id)
    {
        $guru = GuruModel::find($id);

        if (!$guru) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        }

        $guru->akun()->delete();
        $guru->delete();

        return response()->json(['message' => 'Akun dan data guru berhasil dihapus'], 200);
    }
}
