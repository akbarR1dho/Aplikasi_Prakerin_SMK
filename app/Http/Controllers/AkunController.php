<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    //
    public function tambahAkunGuru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nip' => 'unique:guru,nip',
            'email' => 'required|email|unique:guru,email',
            'no_telp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->route('akun-guru.tambah')->withErrors($validator)->withInput();
        }

        $nama_awal = explode(' ', trim($request->nama))[0];

        if (empty($request->nip)) {
            $random_number = DB::table(DB::raw("(SELECT LPAD(CAST(FLOOR(random() * 10000) AS TEXT), 4, '0') AS nip_candidate) as tmp"))
                ->whereRaw("NOT EXISTS (SELECT 1 FROM guru WHERE LEFT(nip, 4) = tmp.nip_candidate)")
                ->value('nip_candidate') ?? str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        } else {
            $random_number = substr($request->nip, 0, 4);
        }

        $akun = User::create([
            'username' => $nama_awal . $random_number,
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

        try {
            $guru->akun()->delete();
            $guru->delete();

            return response()->json(['message' => 'Akun dan data guru berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function editAkunGuru(Request $request, $id)
    {
        $guru = GuruModel::find($id);

        if (!$guru) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'nip' => 'unique:guru,nip,' . $guru->id,
            'email' => 'required|email|unique:guru,email,' . $guru->id,
            'no_telp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        if ($validator->fails()) {
            return redirect()->route('akun-guru.form-edit', $id)->withErrors($validator)->withInput();
        }

        $nama_lama = explode(' ', trim($guru->nama))[0];
        $nip_lama = $guru->nip ? substr($guru->nip, 0, 4) : null;

        $nama_baru = explode(' ', trim($request->nama))[0];
        $nip_baru = $request->nip ? substr($request->nip, 0, 4) : null;

        if ($nama_baru !== $nama_lama || $nip_baru !== $nip_lama) {
            $akun = $guru->akun;

            if ($nip_baru === null && $nip_lama !== $nip_baru) {
                $random_number = DB::table(DB::raw("(SELECT LPAD(CAST(FLOOR(random() * 10000) AS TEXT), 4, '0') AS nip_candidate) as tmp"))
                    ->whereRaw("NOT EXISTS (SELECT 1 FROM guru WHERE LEFT(nip, 4) = tmp.nip_candidate)")
                    ->value('nip_candidate') ?? str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
            } else {
                $random_number = substr($request->nip, 0, 4);
            }

            $akun->username = $nama_baru . $random_number;
            $akun->save();
        }

        try {
            $guru->update([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'jenis_kelamin' => $request->jenis_kelamin,
            ]);

            return redirect()->route('akun-guru.index')->with('success', 'Data guru berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('akun-guru.form-edit', $id)->with('error', 'Gagal mengubah data guru');
        }
    }

    public function gantiPasswordGuru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $guru = GuruModel::find($request->id);
            $guru->akun()->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['message' => 'Password berhasil diubah']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengubah password'], 500);
        }
    }
}
