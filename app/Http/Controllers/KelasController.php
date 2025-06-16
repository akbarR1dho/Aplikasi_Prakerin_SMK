<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use App\Models\KelasModel;
use App\Models\RolesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $query = KelasModel::query()->with([
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
        $data = KelasModel::with('jurusan:id,nama', 'walas:id,nama', 'siswa:nis,nama,email,nisn')->find($id);

        if (!$data) {
            return redirect('kelas')->with('error', 'Kelas tidak ditemukan');
        }

        return view('dashboard.kelas.detail', compact('data'));
    }

    public function formTambah()
    {
        $data = [
            'jurusan' => JurusanModel::select('id', 'nama')->get(),
            'walas' => GuruModel::select('id', 'nama')->get(),
        ];

        return view('dashboard.kelas.form-tambah', compact('data'));
    }

    public function loadJurusan(Request $request)
    {
        $perPage = 10; // Jumlah data per halaman
        $page = $request->get('page', 1); // Ambil nomor halaman, default 1

        // Mengambil kata kunci pencarian
        $search = $request->get('q', ''); // Menggunakan 'q' untuk pencarian agar lebih konsisten

        $baseQuery = JurusanModel::select('id', 'nama');

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

        // Query untuk mengambil data guru
        $roleWalas = RolesModel::where('nama', 'walas')->first();

        $tahunMaksimal = now()->year - 1;
        $baseQuery = GuruModel::where(function ($query) use ($roleWalas, $tahunMaksimal) {
            // Kondisi A: Tidak punya role walas
            $query->whereDoesntHave('roles', function ($q) use ($roleWalas) {
                $q->where('roles.id', $roleWalas->id);
            })
                // Kondisi B: Punya role walas, tapi tidak jadi walas dalam 1 tahun terakhir
                ->orWhere(function ($q) use ($roleWalas, $tahunMaksimal) {
                    $q->whereHas('roles', function ($q2) use ($roleWalas) {
                        $q2->where('roles.id', $roleWalas->id);
                    })
                        ->whereNotIn('id', function ($subquery) use ($tahunMaksimal) {
                            $subquery->select('id_walas')
                                ->from('kelas')
                                ->groupBy('id_walas')
                                ->havingRaw('(MAX(angkatan) > ? OR (MAX(angkatan) = ? AND MAX(CAST(tingkat AS INTEGER)) != 12))', [$tahunMaksimal, $tahunMaksimal]);
                        });
                });
        })
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
            'angkatan' => 'required',
            'tingkat' => 'required|in:11,12',
            'kelompok' => 'required|in:A,B,C',
        ]);

        try {
            $jurusan = JurusanModel::find($request->jurusan);
            $idKelas = 'KLS' . $jurusan->kode_jurusan . '-' . $request->tingkat . $request->kelompok . $request->angkatan;
            $cekKelas = KelasModel::where('id_kelas', $idKelas)->exists();

            // Jika sudah ada, kembalikan error
            if ($cekKelas) {
                return redirect()->route('kelas.form-tambah')->with('error', 'Kelas dengan kombinasi tersebut sudah ada.')->withInput();
            }

            // Ambil angkatan dan tingkat terbaru dari guru yang menjadi walas
            $dataWalas = KelasModel::where('id_walas', $request->walas)
                ->select('angkatan', 'tingkat')
                ->orderByDesc('angkatan')
                ->orderByDesc('tingkat')
                ->first();

            $cekAngkatan = false;
            $cekTingkat = false;

            if ($dataWalas !== null) {
                $cekAngkatan = $dataWalas->angkatan > $request->angkatan - 1;

                $cekTingkat = $request->angkatan - $dataWalas->angkatan == 1 &&
                    (($dataWalas->tingkat == 12 && $request->tingkat == 11) || $dataWalas->tingkat == 11);
            }

            if ($cekAngkatan) {
                return redirect()->route('kelas.form-tambah')->with('error', 'Guru ini masih dalam masa walas berdasarkan angkatan yang dipilih. Silakan pilih guru yang lain.')->withInput();
            }

            if ($cekTingkat) {
                return redirect()->route('kelas.form-tambah')->with('error', 'Guru ini tidak dapat menjadi walas berdasarkan angkatan dan tingkat yang dipilih.')->withInput();
            }

            $cekWalas = KelasModel::where('id_walas', $request->walas)->exists();

            KelasModel::create([
                'id_jurusan' => $request->jurusan,
                'kode_jurusan' => $request->kode_jurusan,
                'id_kelas' => $idKelas,
                'id_walas' => $request->walas,
                'angkatan' => $request->angkatan,
                'tingkat' => $request->tingkat,
                'kelompok' => $request->kelompok,
            ]);

            $role = RolesModel::where('nama', 'walas')->first();

            if (!$cekWalas) {
                $guru = GuruModel::find($request->walas);
                $guru->roles()->attach($role->id);
            }

            return redirect()->route('kelas.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->route('kelas.form-tambah')->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    public function dataWalas($id)
    {
        $data = KelasModel::with('walas:id,nama')->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data walas tidak ditemukan'], 404);
        }

        return response()->json($data);
    }

    public function gantiWalas(Request $request)
    {
        try {
            $kelas = KelasModel::findOrFail($request->id);
            $roleWalas = RolesModel::where('nama', 'walas')->first();

            if ($kelas->id_walas === $request->walas) {
                return response()->json(['message' => 'Guru yang dipilih sama. Silakan pilih guru yang lain.'], 400);
            }

            // Ambil angkatan dan tingkat terbaru dari guru yang menjadi walas
            $dataWalas = KelasModel::where('id_walas', $request->walas)
                ->select('angkatan', 'tingkat')
                ->orderByDesc('angkatan')
                ->orderByDesc('tingkat')
                ->first();

            $cekAngkatan = false;
            $cekTingkat = false;

            if ($dataWalas !== null) {
                $cekAngkatan = $dataWalas->angkatan > $kelas->angkatan - 1;

                $cekTingkat = (($dataWalas->tingkat == 12 && $kelas->tingkat == 11) ||
                    ($dataWalas->tingkat == 11 && ($kelas->tingkat == 11 || $kelas->tingkat == 12))) &&
                    $kelas->angkatan - $dataWalas->angkatan == 1;
            }

            if ($cekAngkatan) {
                return response()->json(['message' => 'Guru ini masih dalam masa walas berdasarkan angkatan yang dipilih. Silakan pilih guru yang lain.'], 400);
            }

            if ($cekTingkat) {
                return response()->json(['message' => 'Guru ini tidak dapat menjadi walas berdasarkan angkatan dan tingkat yang dipilih. Silakan pilih guru yang lain.'], 400);
            }

            $guruLama = $kelas->id_walas;
            $guruBaru = $request->walas;

            // Cek apakah guru lama tidak ada di kelas lain.
            if ($guruLama && KelasModel::where('id_walas', $guruLama)->where('id', '!=', $request->id)->doesntExist()) {
                GuruModel::find($guruLama)?->roles()->detach($roleWalas->id);
            }

            // Cek apakah guru baru tidak ada di kelas lain.
            if ($guruBaru && KelasModel::where('id_walas', $guruBaru)->doesntExist()) {
                GuruModel::find($guruBaru)?->roles()->attach($roleWalas->id);
            }

            $kelas->update([
                'id_walas' => $request->walas,
            ]);

            return response()->json(['message' => 'Walas berhasil diganti']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengganti walas'], 500);
        }
    }

    public function hapus($id)
    {
        try {
            $kelas = KelasModel::find($id);

            if (!$kelas) {
                return redirect()->route('kelas.index')->with('error', 'Data tidak ditemukan');
            }

            // Cek apakah walas tidak ada di kelas lain.
            $walasTidakAda = KelasModel::where('id_walas', $kelas->id_walas)->where('id', '!=', $kelas->id)->doesntExist();

            if ($walasTidakAda) {
                $roleWalas = RolesModel::where('nama', 'walas')->first();
                GuruModel::find($kelas->id_walas)?->roles()->attach($roleWalas->id);
            }

            $kelas->delete();

            return response()->json(['message' => 'Kelas berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus kelas'], 500);
        }
    }
}
