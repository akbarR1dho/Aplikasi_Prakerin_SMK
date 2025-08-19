<?php

namespace App\Http\Controllers;

use App\Models\JurusanModel;
use App\Models\PengajuanModel;
use App\Services\PrioritasRoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PengajuanController extends Controller
{
    //
    public function index(Request $request, PrioritasRoleService $prioritasRoleService)
    {
        $user = Auth::user();
        $role = $prioritasRoleService->roleUtama(session('role', $user->role->pluck('nama')->toArray()));

        if ($request->ajax()) {
            $query = PengajuanModel::query()->select('id', 'id_siswa', 'status', 'id_pengajuan')->with(['siswa' => function ($q) {
                $q->select('nis', 'nama');
            }]);

            $data = match (true) {
                $role == 'tu' => $query->when($request->jurusan !== null, function ($query) use ($request) {
                    $query->whereHas('siswa.kelas.jurusan', function ($query) use ($request) {
                        $query->where('id', $request->jurusan);
                    });
                })->get(),
                ($role == 'kaprog' && $request->tipe === 'kelas') || $role == 'walas' => $query->where([
                    ['persetujuan_tu', '=', 'setuju'],
                ])->whereHas('siswa.kelas', function ($query) use ($user) {
                    $query->where('id_walas', $user->guru->id);
                })->get(),
                $role == 'kaprog' => $query->where([
                    ['persetujuan_tu', '=', 'setuju'],
                    ['persetujuan_walas', '=', 'setuju'],
                ])->whereHas('siswa.kelas.jurusan', function ($query) use ($user) {
                    $query->where('id_kaprog', $user->guru->id);
                })->get(),
                $role == 'hubin' => $query->where([
                    ['persetujuan_tu', '=', 'setuju'],
                    ['persetujuan_walas', '=', 'setuju'],
                    ['persetujuan_kaprog', '=', 'setuju'],
                ])->when($request->jurusan !== null, function ($query) use ($request) {
                    $query->whereHas('siswa.kelas.jurusan', function ($query) use ($request) {
                        $query->where('id', $request->jurusan);
                    });
                })->get(),
                default => $query->where('id_siswa', $user->siswa->nis)->get(),
            };

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($role) {
                    // Tombol default untuk semua role
                    $buttons = '<div class="d-none d-lg-block">';

                    // Tombol untuk role siswa
                    if ($role == 'siswa') {
                        $buttons .= '
                        <a href="pengajuan/detail/' . $row->id . '" class="btn btn-primary btn-sm">Detail</a>
                        <a href="pengajuan/edit/' . $row->id . '" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" id="btnHapus" class="btn btn-danger btn-sm" data-id="' . $row->id . '">Hapus</button>';
                    }

                    // Tambahkan tombol khusus untuk Hubin dan Guru
                    if ($role == 'hubin' || $role == 'walas' || $role == 'kaprog' || $role == 'tu') {
                        $buttons .= '
                        <button type="button" class="btn btn-success btn-sm btnSetuju" data-id="' . $row->id . '">Setujui</button>
                        <button type="button" class="btn btn-danger btn-sm btnTolak" data-id="' . $row->id . '">Tolak</button>';
                    }

                    $buttons .= '</div>';

                    // Versi mobile (dropdown)
                    $dropdownButtons = '<div class="d-lg-none dropdown">';

                    // Tombol untuk role siswa
                    if ($role == 'siswa') {
                        $dropdownButtons .= '
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="pengajuan/detail/' . $row->id . '">Detail</a></li>
                            <li><a class="dropdown-item" href="pengajuan/edit/' . $row->id . '">Edit</a></li>
                            <li><button class="dropdown-item" id="btnHapus" type="button" data-id="' . $row->id . '">Hapus</button></li>';
                    }

                    if ($role == 'hubin' || $role == 'tu' || $role == 'guru') {
                        $dropdownButtons .= '
                            <li><button class="dropdown-item btnSetuju" type="button" data-id="' . $row->id . '">Setujui</button></li>
                            <li><button class="dropdown-item btnTolak" type="button" data-id="' . $row->id . '">Tolak</button></li>';
                    }

                    $dropdownButtons .= '
                        </ul>
                    </div>';

                    return $buttons . $dropdownButtons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $view = match ($role) {
            'tu' => view('dashboard.pengajuan.index-tu')
                ->with('data_jurusan', JurusanModel::select('id', 'nama')->get()),
            'hubin' => view('dashboard.pengajuan.index-hubin')
                ->with('data_jurusan', JurusanModel::select('id', 'nama')->get()),
            'walas', 'kaprog' => view('dashboard.pengajuan.index-guru'),
            default => view('dashboard.pengajuan.index'),
        };

        return $view;
    }

    public function formTambah()
    {
        return view('dashboard.pengajuan.form-tambah');
    }

    public function tambah(Request $request)
    {
        $data = $request->validate([
            'nama_industri' => 'required|string|max:100',
            'kontak_industri' => 'required|string|max:50',
            'alamat_industri' => 'required|string|max:255',
        ]);

        try {
            PengajuanModel::create([
                ...$data,
                'id_siswa' => Auth::user()->siswa->nis,
                'id_pengajuan' => 'PKLID - ' . substr(Str::uuid(), 0, 4),
            ]);
            return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('pengajuan.form-tambah')->with('error', 'Gagal menambahkan pengajuan ' . $e->getMessage() . '')->withInput();
        }
    }

    public function setujui($id, Request $request, PrioritasRoleService $prioritasRoleService)
    {
        $user = Auth::user();
        $role = $prioritasRoleService->roleUtama(session('role', $user->role->pluck('nama')->toArray()));
        $statusPengajuan = PengajuanModel::where('id', $id)->first();

        // Cek apakah pengajuan ditemukan
        if (!$statusPengajuan) {
            return response()->json(['message' => 'Pengajuan tidak ditemukan'], 404);
        }

        // Cek apakah pengajuan sudah disetujui
        if ($statusPengajuan->status == 'setuju') {
            return response()->json(['message' => 'Pengajuan sudah disetujui'], 400);
        }

        // Mapping pesan error jika sudah menyetujui
        $messageSudahMenyetujui = [
            'tu' => 'Anda (TU) sudah menyetujui pengajuan ini sebelumnya',
            'walas' => 'Anda (Wali Kelas) sudah menyetujui pengajuan ini sebelumnya',
            'kaprog' => 'Anda (Kaprog) sudah menyetujui pengajuan ini sebelumnya',
        ];

        // Validasi persetujuan sebelumnya
        $cekValidasi = [
            'walas' => ['tu'],
            'kaprog' => ['walas', 'tu'],
            'hubin' => ['kaprog', 'walas', 'tu']
        ];

        // Cek jika role sudah menyetujui sebelumnya
        if ($role !== 'hubin' && $statusPengajuan->{"persetujuan_$role"} === 'setuju') {
            return response()->json(['message' => $messageSudahMenyetujui[$role]], 400);
        }

        // Cek persetujuan sebelumnya untuk setiap role
        if (isset($cekValidasi[$role])) {
            $roleTidakMenyetujui = [];

            // Khusus untuk role kaprog dengan request tipe kelas dan tu belum menyetujui
            if ($role == 'kaprog' && $request->tipe === 'kelas' && $statusPengajuan->persetujuan_tu !== 'setuju') {
                $roleTidakMenyetujui[] = 'tu';
            } else {
                foreach ($cekValidasi[$role] as $checkRole) {
                    // Cek apakah role belum menyetujui tanpa request tipe kelas
                    if ($statusPengajuan->{"persetujuan_$checkRole"} !== 'setuju' && $request->tipe !== 'kelas') {
                        $roleTidakMenyetujui[] = $checkRole; // Simpan role yang belum menyetujui
                    }
                }
            }

            if (!empty($roleTidakMenyetujui)) {
                // Gabungkan semua role yang belum menyetujui dalam satu pesan
                $errorMessage = 'Pengajuan belum disetujui oleh: ' . implode(', ', $roleTidakMenyetujui);
                return response()->json(['message' => $errorMessage], 400);
            }
        }

        // Eksekusi update
        $updateData = match (true) {
            $role == 'tu' => ['persetujuan_tu' => 'setuju'],
            $role == 'walas' || ($role == 'kaprog' && $request->tipe === 'kelas') => ['persetujuan_walas' => 'setuju'],
            $role == 'kaprog' => ['persetujuan_kaprog' => 'setuju'],
            $role == 'hubin' => ['status' => 'disetujui'],
            default => null
        };

        if ($updateData && $statusPengajuan->update($updateData)) {
            return response()->json(['message' => 'Pengajuan berhasil disetujui'], 200);
        }

        return response()->json(['message' => 'Gagal menyimpan persetujuan'], 500);
    }
}
