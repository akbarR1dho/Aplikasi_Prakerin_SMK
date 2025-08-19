<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\KelasModel;
use App\Models\PengaturanModel;
use App\Models\RoleModel;
use App\Models\SiswaModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = SiswaModel::query()->with([
                'kelas' => function ($query) {
                    $query->select('id', 'id_kelas');
                }
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-none d-lg-block">
                        <a href="akun-siswa/detail/' . $row->nis . '" class="btn btn-primary btn-sm">Detail</a>
                        <a href="akun-siswa/edit/' . $row->nis . '" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" id="btnHapus" class="btn btn-danger btn-sm" data-id="' . $row->nis . '">Hapus</button>
                        <button type="button" class="btn btn-warning btn-sm btnResetPassword" data-id="' . $row->nis . '">Reset Password</button>
                    </div>
                
                    <div class="d-lg-none dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="akun-siswa/detail/' . $row->nis . '">Detail</a></li>
                            <li><a class="dropdown-item" href="akun-siswa/edit/' . $row->nis . '">Edit</a></li>
                            <li><button class="dropdown-item" id="btnHapus" type="button" data-id="' . $row->nis . '">Hapus</button></li>
                            <li><button class="dropdown-item btnResetPassword" type="button" data-id="' . $row->nis . '">Reset Password</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.akun_siswa.index');
    }

    public function loadKelas(Request $request)
    {
        $perPage = 10; // Jumlah data per halaman
        $page = $request->get('page', 1); // Ambil nomor halaman, default 1

        // Mengambil kata kunci pencarian
        $search = $request->get('q', ''); // Menggunakan 'q' untuk pencarian agar lebih konsisten

        $baseQuery = KelasModel::select('id', 'id_kelas');

        if (!empty($search)) {
            // Menambahkan kondisi pencarian jika ada kata kunci
            $baseQuery->where('id_kelas', 'ILIKE', '%' . $search . '%');
        }

        // Menghitung total data untuk menentukan apakah ada halaman berikutnya
        $total = $baseQuery->count();

        // Ambil data dengan pagination
        $kelas = $baseQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Menentukan apakah ada halaman berikutnya
        $nextPageUrl = ($page * $perPage) < $total ? url()->current() . '?page=' . ($page + 1) : null;

        // Mengembalikan data ke frontend
        return response()->json([
            'data' => $kelas,
            'next_page_url' => $nextPageUrl, // Kirim URL halaman berikutnya jika ada
            'total' => $total
        ]);
    }

    public function formTambah()
    {
        return view('dashboard.akun_siswa.form-tambah');
    }

    public function formEdit($nis)
    {
        try {
            $siswa = SiswaModel::with([
                'akun' => function ($query) {
                    $query->select('id', 'email', 'username');
                },
                'kelas' => function ($query) {
                    $query->select('id', 'id_kelas');
                }
            ])->findOrFail($nis);

            return view('dashboard.akun_siswa.form-edit', compact('siswa'));
        } catch (\Exception $e) {
            return redirect()->route('akun-siswa.index')->with('error', 'Akun siswa tidak ditemukan');
        }
    }

    public function tambah(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:akun,email',
            'nisn' => 'required|unique:siswa,nisn|digits:10',
            'nis' => 'required|unique:siswa,nis',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telp' => 'required|string|regex:/^[0-9\+]+$/',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|integer|digits:4',
            'alamat' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id',
        ]);

        try {
            DB::transaction(function () use ($request, $data) {
                // Membuat akun siswa
                $namaAwal = explode(' ', trim($request->nama))[0];
                $akun = User::create([
                    'username' => $namaAwal . substr(Str::uuid(), 0, 4),
                    'email' => $request->email,
                    'password' => PengaturanModel::get('app_default_password'),
                ]);
                $role = RoleModel::where('nama', 'siswa')->firstOrFail();
                $akun->role()->attach($role->id);

                // Membuat data siswa
                SiswaModel::create([
                    ...$data,
                    'id_akun' => $akun->id
                ]);
            });

            return redirect()->route('akun-siswa.index')->with('success', 'Akun dan data siswa berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->route('akun-siswa.form-tambah')->with('error', 'Gagal membuat data siswa' . $e)->withInput();
        }
    }

    public function edit(Request $request, $nis)
    {
        $siswa = SiswaModel::findOrFail($nis);

        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'username' => 'required|string|unique:akun,username,' . $nis->id_akun . ',nis',
            'email' => 'required|email|unique:akun,email,' . $nis->id_akun . ',nis',
            'nisn' => 'required|unique:siswa,nisn,' . $nis . ',nis|digits:10',
            'nis' => 'required|unique:siswa,nis,' . $nis . ',nis',
            'jenis_kelamin' => 'in:L,P',
            'no_telp' => 'required|numeric',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|integer|digits:4',
            'alamat' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id',
        ]);

        try {
            DB::transaction(function () use ($data, $siswa) {
                // Memperbarui data dan akun siswa
                $siswa->akun->update($data);
                $siswa->update($data);
            });

            return redirect()->route('akun-siswa.index')->with('success', 'Data siswa berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('akun-siswa.form-edit', $nis)->with('error', 'Gagal mengubah data siswa')->withInput();
        }
    }

    public function detail($nis)
    {
        try {
            $siswa = SiswaModel::with([
                'akun' => function ($query) {
                    $query->select('id', 'username', 'email');
                },
                'kelas' => function ($query) {
                    $query->select('id', 'id_kelas');
                }
            ])->findOrFail($nis);

            return view('dashboard.akun_siswa.detail', compact('siswa'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('akun-siswa.index')->with('error', 'Akun siswa tidak ditemukan');
        }
    }

    public function hapus($nis)
    {
        try {
            DB::transaction(function () use ($nis) {
                $siswa = SiswaModel::findOrFail($nis);
                $siswa->akun()->delete();
                $siswa->delete();
            });

            return response()->json(['message' => 'Akun dan data siswa berhasil dihapus'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('akun-siswa.index')->with('error', 'Akun siswa tidak ditemukan');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data siswa', 'error' => $e], 500);
        }
    }

    public function resetPassword($nis)
    {
        try {
            DB::transaction(function () use ($nis) {
                $siswa = SiswaModel::findOrFail($nis);
                $akun = $siswa->akun;

                $akun->update([
                    'password' => PengaturanModel::get('app_default_password')
                ]);
            });

            return response()->json(['message' => 'Password berhasil direset'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mereset password'], 500);
        }
    }

    public function formImport()
    {
        return view('dashboard.akun_siswa.form-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $import = new SiswaImport();
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
                    ->route('akun-siswa.form-import')
                    ->with('warning', 'Sebagian data berhasil diimport')
                    ->with('import_errors', $messages);
            }

            DB::commit();

            return redirect()
                ->route('akun-siswa.index')
                ->with('success', 'Semua data berhasil diimport');
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = 'Import gagal: ' . $e->getMessage();

            // Jika ada error validasi dari Excel
            if ($e instanceof \Maatwebsite\Excel\Validators\ValidationException) {
                $errorMessage = 'Validasi data gagal: ' . $e->getMessage();
            }

            return redirect()
                ->route('akun-siswa.form-import')
                ->with('error', $errorMessage)
                ->withInput();
        }
    }
}
