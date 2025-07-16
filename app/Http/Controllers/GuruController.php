<?php

namespace App\Http\Controllers;

use App\Imports\GuruImport;
use App\Models\GuruModel;
use App\Models\PengaturanModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class GuruController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = GuruModel::query()->select('id', 'nama', 'nip')->get();

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
        $data = $request->validate([
            'nama' => 'required',
            'nip' => 'unique:guru,nip|digits:18|nullable',
            'email' => 'required|email|unique:akun,email',
            'no_telp' => 'required|string|regex:/^[0-9\+]+$/',
            'jenis_kelamin' => 'required|in:L,P',
        ], [
            'nama.required' => 'Nama harus diisi',
            'nip.required' => 'NIP harus diisi',
            'nip.unique' => 'NIP sudah terdaftar',
            'nip.digits' => 'NIP harus terdiri dari 18 angka dan tidak boleh lebih',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'jenis_kelamin.in' => 'Jenis kelamin hanya boleh Laki-laki atau Perempuan',
        ]);

        try {
            DB::transaction(function () use ($request, $data) {
                // Membuat akun guru
                $namaAwal = explode(' ', trim($request->nama))[0];
                $akun = User::create([
                    'username' => $namaAwal . substr(Str::uuid(), 0, 4),
                    'email' => $request->email,
                    'password' => PengaturanModel::get('app_default_password'),
                    'role' => 'guru',
                ]);

                // Membuat data guru
                GuruModel::create([
                    ...$data,
                    'id_akun' => $akun->id
                ]);
            });

            return redirect()->route('akun-guru.index')->with('success', 'Akun dan data guru berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->route('akun-guru.form-tambah')->with('error', 'Gagal membuat data guru')->withInput();
        }
    }

    public function hapus($id)
    {
        try {
            $guru = GuruModel::findOrFail($id);
            $guru->akun()->delete();

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

        $data = $request->validate([
            'nama' => 'required',
            'username' => 'required|string|unique:akun,username,' . $guru->id_akun,
            'nip' => 'unique:guru,nip,' . $guru->id . '|max:18|nullable',
            'email' => 'required|email|unique:akun,email,' . $guru->id_akun,
            'no_telp' => 'required|string|regex:/^[0-9\+]+$/',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        try {
            $akun = $guru->akun;

            if($guru->nama != $request->nama && $akun->username == $request->username){
                // Update username akun guru
                $namaAwal = explode(' ', trim($request->nama))[0];
                $akun->update([
                    'username' => $namaAwal . substr(Str::uuid(), 0, 4)
                ]);
            }

            // Update data dan akun guru
            $akun->update($data);
            $guru->update($data);

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
            return response()->json(['message' => 'Gagal mereset password'], 500);
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
        ], [
            'file.required' => 'File harus diisi',
            'file.mimes' => 'Format file harus CSV, XLS, atau XLSX',
            'file.max' => 'Ukuran file maksimal 2MB',
        ]);

        DB::beginTransaction();

        try {
            $import = new GuruImport();
            $import->import($request->file('file'));

            // Hitung error
            $failures = $import->failures();
            $jumlahError = collect($failures)
                ->map(fn($fail) => $fail->row())
                ->filter()
                ->unique()
                ->count();

            $jumlahBaris = $import->totalRows;

            // Jika semua baris error, rollback
            if ($jumlahError === $jumlahBaris && $jumlahBaris > 0) {
                throw new \Exception('Semua data gagal divalidasi');
            }

            // Jika ada error parsial
            if ($failures->isNotEmpty()) {
                DB::commit(); // Commit data yang valid

                $messages = $failures->map(function ($failure) {
                    return "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                })->toArray();

                return redirect()
                    ->route('akun-guru.form-import')
                    ->with('warning', 'Sebagian data berhasil diimport')
                    ->with('import_errors', $messages);
            }

            DB::commit();

            return redirect()
                ->route('akun-guru.index')
                ->with('success', 'Semua data berhasil diimport');
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = 'Import gagal: ' . $e->getMessage();

            // Jika ada error validasi dari Excel
            if ($e instanceof \Maatwebsite\Excel\Validators\ValidationException) {
                $errorMessage = 'Validasi data gagal: ' . $e->getMessage();
            }

            return redirect()
                ->route('akun-guru.form-import')
                ->with('error', $errorMessage)
                ->withInput();
        }
    }
}
