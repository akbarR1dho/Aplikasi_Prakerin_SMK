<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use App\Models\KelasModel;
use App\Models\RolesModel;
use App\Models\SiswaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
{
    protected $kelasModel;
    protected $guruModel;
    protected $jurusanModel;
    protected $rolesGuruModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->guruModel = new GuruModel();
        $this->jurusanModel = new JurusanModel();
        $this->rolesGuruModel = new RolesModel();
    }

    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = $this->kelasModel->query()->with([
                'jurusan' => function ($query) {
                    $query->select('id', 'nama');
                },
                'walas' => function ($query) {
                    $query->select('id', 'nama');
                }
            ]);

            if ($request->angkatan) {
                $query->where('angkatan', $request->angkatan);
            }

            if ($request->tingkat) {
                $query->where('tingkat', $request->tingkat);
            }

            if ($request->kelompok) {
                $query->where('kelompok', $request->kelompok);
            }

            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-none d-lg-block">
                        <a href="kelas/detail/' . $row->id . '" class="btn btn-primary btn-sm">Detail</a>
                        <button type="button" id="btnGantiWalas" class="edit btn btn-warning btn-sm" 
                            data-bs-toggle="modal" data-bs-target="#modalGantiWalas" 
                            data-id="' . $row->id . '">Ganti Walas
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id="btnHapus" data-id="' . $row->id . '">Hapus</button>
                    </div>
                
                    <div class="d-lg-none dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="kelas/detail/' . $row->id . '">Detail</a></li>
                            <li><button type="button" id="btnGantiWalas" class="dropdown-item"
                                data-bs-toggle="modal" data-bs-target="#modalGantiWalas" 
                                data-id="' . $row->id . '">Ganti Walas
                            </button></li>
                            <li><button class="dropdown-item" id="btnHapus" type="button" data-id="' . $row->id . '">Hapus</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.kelas.index');
    }

    public function detail($id)
    {
        $data = $this->kelasModel->with('jurusan:id,nama', 'walas:id,nama')->find($id);

        if (!$data) {
            return redirect('kelas')->with('error', 'Kelas tidak ditemukan');
        }

        return view('dashboard.kelas.detail', compact('data'));
    }

    public function dataSiswa(Request $request, $id)
    {
        if ($request->ajax()) {

            $data = SiswaModel::query()->where('id_kelas', $id)->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-none d-lg-block">
                        <a href="/akun-siswa/detail/' . $row->nis . '" class="btn btn-primary btn-sm">Detail</a>
                        <a href="/akun-siswa/edit/' . $row->nis . '" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" id="btnHapus" class="btn btn-danger btn-sm" data-id="' . $row->nis . '">Hapus</button>
                        <button type="button" class="btn btn-warning btn-sm btnResetPassword" data-id="' . $row->nis . '">Reset Password</button>
                    </div>
                
                    <div class="d-lg-none dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/akun-siswa/detail/' . $row->nis . '">Detail</a></li>
                            <li><a class="dropdown-item" href="/akun-siswa/edit/' . $row->nis . '">Edit</a></li>
                            <li><button class="dropdown-item" id="btnHapus" type="button" data-id="' . $row->nis . '">Hapus</button></li>
                            <li><button class="dropdown-item btnResetPassword" type="button" data-id="' . $row->nis . '">Reset Password</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function formTambah()
    {
        $data = [
            'jurusan' => $this->jurusanModel->select('id', 'nama')->get(),
            'walas' => $this->guruModel->select('id', 'nama')->get(),
        ];

        return view('dashboard.kelas.form-tambah', compact('data'));
    }

    public function loadJurusan(Request $request)
    {
        $perPage = 10; // Jumlah data per halaman
        $page = $request->get('page', 1); // Ambil nomor halaman, default 1

        // Mengambil kata kunci pencarian
        $search = $request->get('q', ''); // Menggunakan 'q' untuk pencarian agar lebih konsisten

        $baseQuery = $this->jurusanModel->select('id', 'nama');

        if (!empty($search)) {
            // Menambahkan kondisi pencarian jika ada kata kunci
            $baseQuery->where('nama', 'ILIKE', '%' . $search . '%');
        }

        // Menghitung total data untuk menentukan apakah ada halaman berikutnya
        $total = $baseQuery->count();

        // Ambil data dengan pagination
        $jurusan = $baseQuery->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        // Menentukan apakah ada halaman berikutnya
        $nextPageUrl = ($page * $perPage) < $total ? url()->current() . '?page=' . ($page + 1) : null;

        // Mengembalikan data ke frontend
        return response()->json([
            'data' => $jurusan,
            'next_page_url' => $nextPageUrl, // Kirim URL halaman berikutnya jika ada
            'total' => $total
        ]);
    }

    public function loadWalas(Request $request)
    {
        $perPage = 10; // Jumlah data per halaman

        // Mengambil kata kunci pencarian
        $search = $request->get('q', ''); // Menggunakan 'q' untuk pencarian agar lebih konsisten

        $baseQuery = $this->guruModel
            ->select('id', 'nama')
            ->when($search, fn($q) => $q->where('nama', 'ILIKE', "%{$search}%"))
            ->orderBy('nama');

        // Ambil data dengan pagination
        $walas = $baseQuery->simplePaginate($perPage);

        // Mengembalikan data ke frontend
        return response()->json([
            'data' => $walas->items(),
            'next_page_url' => $walas->nextPageUrl(),
        ]);
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'jurusan' => 'required',
            'angkatan' => 'required|digits:4',
            'tingkat' => 'required|in:11,12',
            'kelompok' => 'required|in:A,B,C',
        ]);

        try {
            $jurusan = $this->jurusanModel->find($request->jurusan);
            $idKelas = 'KLS' . $jurusan->kode_jurusan . '-' . $request->tingkat . $request->kelompok . $request->angkatan;
            $cekKelas = $this->kelasModel->where('id_kelas', $idKelas)->exists();

            // Jika sudah ada, kembalikan error
            if ($cekKelas) {
                return redirect()->route('kelas.form-tambah')->with('error', 'Kelas dengan kombinasi tersebut sudah ada.')->withInput();
            }

            // Ambil angkatan dan tingkat terbaru dari guru yang menjadi walas
            $dataWalas = $this->kelasModel->where('id_walas', $request->walas)
                ->select('angkatan')
                ->latest('angkatan')
                ->first();

            if ($dataWalas) {
                $masihAktif = ($request->angkatan - $dataWalas->angkatan) < 3;

                if ($masihAktif) {
                    return redirect()->route('kelas.form-tambah')->with('error', 'Guru ini masih dalam masa walas. Silakan pilih guru yang lain.')->withInput();
                }

                $angkatanSama = $dataWalas->angkatan == $request->angkatan;

                if ($angkatanSama) {
                    return redirect()->route('kelas.form-tambah')->with('error', 'Walas ini sudah dalam angkatan yang sama dengan kelas ini.')->withInput();
                }
            }

            DB::transaction(function () use ($request, $idKelas) {
                $cekWalas = $this->kelasModel->where('id_walas', $request->walas)->exists();

                $this->kelasModel->create([
                    'id_jurusan' => $request->jurusan,
                    'kode_jurusan' => $request->kode_jurusan,
                    'id_kelas' => $idKelas,
                    'id_walas' => $request->walas,
                    'angkatan' => $request->angkatan,
                    'tingkat' => $request->tingkat,
                    'kelompok' => $request->kelompok,
                ]);

                $role = $this->rolesGuruModel->firstOrCreate(['nama' => 'walas']);

                if (!$cekWalas) {
                    $this->guruModel->find($request->walas)->roles()->syncWithoutDetaching($role->id);
                }
            });

            return redirect()->route('kelas.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->route('kelas.form-tambah')->with('error', 'Terjadi kesalahan saat menyimpan data')->withInput();
        }
    }

    public function dataWalas($id)
    {
        $data = $this->kelasModel->with('walas:id,nama')->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data walas tidak ditemukan'], 404);
        }

        return response()->json($data);
    }

    public function gantiWalas(Request $request)
    {
        try {
            $kelas = $this->kelasModel->with('walas')->findOrFail($request->id);
            $roleWalas = $this->rolesGuruModel->where('nama', 'walas')->first();

            if ($kelas->id_walas === $request->walas) {
                return response()->json(['message' => 'Guru yang dipilih sama. Silakan pilih guru yang lain.'], 400);
            }

            // Ambil angkatan dan tingkat terbaru dari guru yang menjadi walas
            $dataWalas = $this->kelasModel->where('id_walas', $request->walas)
                ->select('angkatan')
                ->latest('angkatan')
                ->first();

            if ($dataWalas) {
                $masihAktif = ($dataWalas->angkatan == now()->year && now()->month < 7) || ($dataWalas->angkatan > now()->year);

                if ($masihAktif) {
                    return response()->json(['message' => 'Guru ini masih dalam masa walas. Silakan pilih guru yang lain.'], 400);
                }

                $angkatanSama = $dataWalas->angkatan == $kelas->angkatan;

                if ($angkatanSama) {
                    return response()->json(['message' => 'Walas ini sudah dalam angkatan yang sama dengan kelas ini.'], 400);
                }
            }

            DB::transaction(function () use ($kelas, $request, $roleWalas) {
                // Hapus role walas dari guru lama jika tidak menjadi walas di kelas lain
                if ($kelas->walas()->kelas()->where('id', '!=', $kelas->id)->doesntExists()) {
                    $kelas->walas()->roles()->detach($roleWalas->id);
                }

                // Update walas baru
                $kelas->update(['id_walas' => $request->walas]);

                // Tambahkan role ke guru baru
                $kelas->load('walas');
                $kelas->walas()->roles()->syncWithoutDetaching([$roleWalas->id]);
            });

            return response()->json(['message' => 'Walas berhasil diganti.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengganti walas.'], 500);
        }
    }

    public function hapus($id)
    {
        try {
            $kelas = $this->kelasModel->with('walas')->find($id);

            if (!$kelas) {
                return response()->json(['message' => 'Data kelas tidak ditemukan.'], 404);
            }

            // Cek apakah walas ada di kelas lain.
            $walasAda = $this->kelasModel->where('id_walas', $kelas->id_walas)->where('id', '!=', $kelas->id)->exists();

            DB::transaction(function () use ($kelas, $walasAda) {
                if (!$walasAda) {
                    $roleWalas = $this->rolesGuruModel->firstOrCreate(['nama' => 'walas']);
                    $kelas->walas->roles()->detach($roleWalas->id);
                }

                $kelas->delete();
            });

            return response()->json(['message' => 'Kelas berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus kelas.'], 500);
        }
    }
}
