<?php

namespace App\Http\Controllers;

use App\Imports\GuruImport;
use App\Models\GuruModel;
use App\Models\PengaturanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class GuruController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = GuruModel::query()->select('id', 'nama', 'email', 'nip')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-none d-lg-block">
                        <a href="akun-guru/detail/' . $row->id . '" class="btn btn-primary btn-sm">Detail</a>
                        <a href="akun-guru/edit/' . $row->id . '" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" id="btnHapus" class="btn btn-danger btn-sm" data-id="' . $row->id . '">Hapus</button>
                        <button type="button" class="btn btn-warning btn-sm btnResetPassword" data-id="' . $row->id . '">Reset Password</button>
                    </div>
                
                    <div class="d-lg-none dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="akun-guru/detail/' . $row->id . '">Detail</a></li>
                            <li><a class="dropdown-item" href="akun-guru/edit/' . $row->id . '">Edit</a></li>
                            <li><button class="dropdown-item" id="btnHapus" type="button" data-id="' . $row->id . '">Hapus</button></li>
                            <li><button class="dropdown-item btnResetPassword" type="button" data-id="' . $row->id . '">Reset Password</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.akun_guru.index');
    }

    public function formTambah()
    {
        return view('dashboard.akun_guru.form-tambah');
    }

    public function formEdit($id)
    {
        $guru = GuruModel::find($id);

        if (!$guru) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        }

        return view('dashboard.akun_guru.form-edit', compact('guru'));
    }

    public function detail($id)
    {
        $guru = GuruModel::with('roles', 'akun')->find($id);

        if (!$guru) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        }

        return view('dashboard.akun_guru.detail', compact('guru'));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nip' => 'unique:guru,nip|max:18|nullable',
            'email' => 'required|email|unique:guru,email',
            'no_telp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $guru = GuruModel::create([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        if (!$guru) {
            return redirect()->route('akun-guru.tambah')->with('error', 'Gagal membuat data guru');
        }

        return redirect()->route('akun-guru.index')->with('success', 'Akun dan data guru berhasil dibuat');
    }

    private function hpausGelar($nama)
    {
        // Daftar gelar umum (case insensitive)
        $gelar = ['dr', 'dr.', 'dokter', 'prof', 'prof.', 'hj', 'hj.', 'haji', 'ir', 'ir.'];

        // Pisahkan nama menjadi array kata
        $partNama = explode(' ', trim($nama));

        // Cek jika kata pertama adalah gelar
        $kataAwal = strtolower($partNama[0]);
        if (in_array($kataAwal, $gelar)) {
            // Hapus kata pertama (gelar)
            array_shift($partNama);
        }

        return implode(' ', $partNama);
    }

    public function hapus($id)
    {
        try {
            $guru = GuruModel::findOrFail($id);

            $guru->akun()->delete();
            $guru->delete();

            return response()->json(['message' => 'Akun dan data guru berhasil dihapus'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data guru'], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $guru = GuruModel::find($id);

        if (!$guru) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        }

        $request->validate([
            'nama' => 'required',
            'nip' => 'unique:guru,nip,' . $guru->id . '|max:18|nullable',
            'email' => 'required|email|unique:guru,email,' . $guru->id,
            'no_telp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

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

    public function resetPassword($id)
    {
        try {
            $guru = GuruModel::find($id);

            if (Hash::check(PengaturanModel::get('app_default_password'), $guru->akun->password)) {
                return response()->json(['message' => 'Password default tidak boleh direset'], 400);
            }

            $guru->akun()->update([
                'password' => PengaturanModel::get('app_default_password'),
            ]);

            return response()->json(['message' => 'Password berhasil direset']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat reset password'], 500);
        }
    }

    public function formImport()
    {
        return view('dashboard.akun_guru.form-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|max:2048',
        ]);

        $import = new GuruImport();
        $import->import($request->file('file'));

        $jumlahError = collect($import->failures())
            ->map(fn($fail) => $fail->row())
            ->filter() // buang null
            ->unique()
            ->count();
        $jumlahBaris = $import->totalRows;
        $message = null;
        $status = null;

        if ($jumlahError === $jumlahBaris && $jumlahBaris > 0) {
            $message = 'Semua data gagal diimport';
            $status = 'error';
        } elseif ($jumlahError > 0 && $jumlahError < $jumlahBaris) {
            $message = 'Sebagian data berhasil diimport';
            $status = 'warning';
        } else {
            $message = 'Semua data berhasil diimport';
            $status = 'success';
        }

        if ($import->failures()->isNotEmpty()) {
            $messages = [];

            foreach ($import->failures() as $failure) {
                $messages[] = "Baris {$failure->row()}: " . implode(', ', $failure->errors());
            }

            return redirect()->route('akun-guru.form-import')
                ->with($status, $message) // flash utama
                ->with('import_errors', $messages); // kumpulan error detail
        }


        return redirect()->route('akun-guru.index')->with($status, $message);
    }
}
