<?php

namespace App\Http\Controllers;

use App\Models\PengaturanModel;
use App\Models\RoleModel;
use App\Models\TuModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TuController extends Controller
{
    protected $tuModel;
    // protected $guruModel;
    protected $akunModel;
    protected $roleModel;

    public function __construct()
    {
        $this->tuModel = new TuModel();
        // $this->guruModel = new GuruModel();
        $this->akunModel = new User();
        $this->roleModel = new RoleModel();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->tuModel->query()->select('id', 'nama', 'nip')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-none d-lg-block">
                        <a href="akun-tu/detail/' . $row->id . '" class="btn btn-primary btn-sm">Detail</a>
                        <a href="akun-tu/edit/' . $row->id . '" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" id="btnHapus" class="btn btn-danger btn-sm" data-id="' . $row->id . '">Hapus</button>
                        <button type="button" class="btn btn-warning btn-sm btnResetPassword" data-id="' . $row->id . '">Reset Password</button>
                    </div>
                
                    <div class="d-lg-none dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="akun-tu/detail/' . $row->id . '">Detail</a></li>
                            <li><a class="dropdown-item" href="akun-tu/edit/' . $row->id . '">Edit</a></li>
                            <li><button class="dropdown-item" id="btnHapus" type="button" data-id="' . $row->id . '">Hapus</button></li>
                            <li><button class="dropdown-item btnResetPassword" type="button" data-id="' . $row->id . '">Reset Password</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.akun_tu.index');
    }

    public function formTambah()
    {
        return view('dashboard.akun_tu.form-tambah');
    }

    public function tambah(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'nip' => 'unique:tu,nip|digits:18|nullable',
            'email' => 'required|email|unique:akun,email',
            'no_telp' => 'required|string|regex:/^[0-9\+]+$/',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        try {

            DB::transaction(function () use ($request, $data) {
                $namaAwal = explode(' ', trim($request->nama))[0];
                // Membuat akun tu
                $akun = $this->akunModel->create([
                    'username' => $namaAwal . substr(Str::uuid(), 0, 4),
                    'email' => $request->email,
                    'password' => PengaturanModel::get('app_default_password'),
                ]);
                $role = $this->roleModel->where('nama', 'tu')->first();
                $akun->role()->attach($role->id);

                // Membuat data tu
                $this->tuModel->create([
                    ...$data,
                    'id_akun' => $akun->id
                ]);
            });

            return redirect()->route('akun-tu.index')->with('success', 'Akun dan data tu berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->route('akun-tu.form-tambah')->with('error', 'Gagal membuat data tu' . $e->getMessage())->withInput();
        }
    }
}
