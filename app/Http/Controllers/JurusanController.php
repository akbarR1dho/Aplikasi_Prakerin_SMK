<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use App\Models\RolesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class JurusanController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = JurusanModel::query()->with(['kaprog' => function ($query) {
                $query->select('id', 'nama');
            }])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-none d-lg-block">
                        <button type="button" id="btnEdit" class="edit btn btn-warning btn-sm" 
                            data-bs-toggle="modal" data-bs-target="#modalForm" data-id="' . $row->id . '">Edit</button>
                        <button type="button" id="btnHapus" class="btn btn-danger btn-sm" data-id="' . $row->id . '">Hapus</button>
                    </div>
                
                    <div class="d-lg-none dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><button type="button" id="btnEdit" 
                                data-bs-toggle="modal" class="dropdown-item" data-bs-target="#modalForm" data-id="' . $row->id . '">Edit</button></li>
                            <li><button type="button" class="dropdown-item" id="btnHapus" data-id="' . $row->id . '">Hapus</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.jurusan.index');
    }

    public function loadKaprog(Request $request)
    {
        $perPage = 10; // Jumlah data per halaman

        // Mengambil kata kunci pencarian
        $search = $request->get('q', ''); // Menggunakan 'q' untuk pencarian agar lebih konsisten

        // Query untuk mengambil data guru
        $roleKaprog = RolesModel::where('nama', 'kaprog')->first();

        $baseQuery = GuruModel::whereDoesntHave('roles', function ($query) use ($roleKaprog) {
            $query->where('roles.id', $roleKaprog->id);
        }) // Filter guru yang belum memiliki role kaprog
            ->select('id', 'nama')
            ->when($search, fn($q) => $q->where('nama', 'ILIKE', "%{$search}%"))
            ->orderBy('nama'); // Mengurutkan berdasarkan nama

        // Ambil data dengan pagination
        $guru = $baseQuery->simplePaginate($perPage);

        // Mengembalikan data ke frontend
        return response()->json([
            'data' => $guru->items(),
            'next_page_url' => $guru->nextPageUrl(), // Kirim URL halaman berikutnya jika ada
        ]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'kode_jurusan' => 'required',
            'id_kaprog' => 'required|exists:guru,id',
        ]);

        $roleKaprog = RolesModel::where('nama', 'kaprog')->first();

        try {
            DB::transaction(function () use ($request, $roleKaprog, $data) {
                if ($request->id != null) {
                    $jurusan = JurusanModel::with('kaprog')->find($request->id);

                    if ($jurusan->id_kaprog !== $request->id_kaprog) {
                        $jurusan->kaprog->roles()->detach($roleKaprog->id);
                    }

                    // Update data jurusan
                    $jurusan->update($data);

                    // Update role kaprog
                    $newKaprog = GuruModel::find($request->id_kaprog);
                    $newKaprog->roles()->syncWithoutDetaching($roleKaprog->id);
                } else {
                    $jurusan = JurusanModel::create($data);

                    // Tambahkan role kaprog
                    $newKaprog = GuruModel::find($request->id_kaprog);
                    $newKaprog->roles()->syncWithoutDetaching($roleKaprog->id);
                }
            });

            return response()->json(['message' => $request->id ? 'Jurusan berhasil diperbarui' : 'Jurusan berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function hapus($id)
    {
        try {
            $jurusan = JurusanModel::find($id);

            $roleKaprog = RolesModel::where('nama', 'kaprog')->first();
            $guru = GuruModel::find($jurusan->id_kaprog);
            $guru->roles()->detach($roleKaprog->id);

            $jurusan->delete();
            return response()->json(['message' => 'Jurusan berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus jurusan'], 500);
        }
    }

    public function getData($id)
    {
        $data = JurusanModel::with('kaprog:id,nama')->find($id);

        if (!$data) {
            return response()->json(['message' => 'Jurusan tidak ditemukan'], 404);
        }

        return response()->json($data);
    }
}
