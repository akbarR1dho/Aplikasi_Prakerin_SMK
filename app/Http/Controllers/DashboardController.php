<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    //
    public function home()
    {
        return view('dashboard.home');
    }

    public function akunGuru(Request $request)
    {
        if ($request->ajax()) {

            $data = GuruModel::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-none d-md-block">
                        <a href="akun-guru/detail/' . $row->id . '" class="btn btn-primary btn-sm">Detail</a>
                        <a href="akun-guru/edit/' . $row->id . '" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">Hapus</button>
                        <button type="button" class="btn btn-warning btn-sm btnGantiPassword" data-id="' . $row->id . '">Ganti Password</button>
                    </div>
                
                    <div class="d-md-none dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="akun-guru/detail/' . $row->id . '">Detail</a></li>
                            <li><a class="dropdown-item" href="akun-guru/edit/' . $row->id . '">Edit</a></li>
                            <li><button class="dropdown-item delete" type="button" data-id="' . $row->id . '">Hapus</button></li>
                            <li><button class="dropdown-item btnGantiPassword" type="button" data-id="' . $row->id . '">Ganti Password</button></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.akun-guru.index');
    }

    public function showTambahAkunGuru()
    {
        return view('dashboard.akun-guru.form-tambah');
    }

    public function showEditAkunGuru($id)
    {
        $guru = GuruModel::find($id);

        if (!$guru) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        }

        return view('dashboard.akun-guru.form-edit', compact('guru'));
    }

    public function detailAkunGuru($id)
    {
        $guru = GuruModel::with('roles', 'akun')->find($id);

        if (!$guru) {
            return redirect()->route('akun-guru.index')->with('error', 'Akun guru tidak ditemukan');
        }

        return view('dashboard.akun-guru.detail', compact('guru'));
    }

    public function jurusan(Request $request)
    {
        if ($request->ajax()) {

            $data = JurusanModel::query()->with('kaprog');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = ' <button type="button" id="btnEdit" class="edit btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalForm" data-id="' . $row->id . '">Edit</button>';
                    $btn .= ' <button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '">Hapus</button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('dashboard.jurusan.index');
    }
}
