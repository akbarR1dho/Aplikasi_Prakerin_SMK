<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use App\Models\RolesModel;
use Illuminate\Http\Request;
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
            }])->select('id', 'nama', 'kode_jurusan', 'kaprog')->get();

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
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'kode_jurusan' => 'required|max:3',
            'kaprog' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $roleKaprog = RolesModel::where('nama', 'kaprog')->first();

        try {
            if ($request->id != null) {
                $jurusan = JurusanModel::find($request->id);

                if ($jurusan->kaprog !== null) {
                    $guruLama = GuruModel::find($jurusan->getOriginal('kaprog'));
                    $guruLama->roles()->detach($roleKaprog->id);
                }

                $jurusan->update([
                    'nama' => $request->nama,
                    'kode_jurusan' => $request->kode_jurusan,
                    'kaprog' => $request->kaprog,
                ]);

                $guruBaru = GuruModel::find($request->kaprog);
                $guruBaru->roles()->attach($roleKaprog->id);

                return response()->json(['message' => 'Jurusan berhasil diperbarui']);
            } else {
                $jurusan = JurusanModel::create([
                    'nama' => $request->nama,
                    'kode_jurusan' => $request->kode_jurusan,
                    'kaprog' => $request->kaprog,
                ]);

                $guru = GuruModel::find($request->kaprog);
                $guru->roles()->attach($roleKaprog->id);

                return response()->json(['message' => 'Jurusan berhasil ditambahkan']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat ingin simpan jurusan'], 500);
        }
    }

    public function hapus($id)
    {
        try {
            $jurusan = JurusanModel::find($id);

            $roleKaprog = RolesModel::where('nama', 'kaprog')->first();
            $guru = GuruModel::find($jurusan->kaprog);
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
