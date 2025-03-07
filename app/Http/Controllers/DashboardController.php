<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

                    $btn = '<a href="akun-guru/' . $row->id . '" class="edit btn btn-primary btn-sm">Detail</a>';
                    $btn .= ' <a href="akun-guru/' . $row->id . '/edit" class="edit btn btn-warning btn-sm">Edit</a>';
                    $btn .= ' <button type="button" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '">Hapus</button>';

                    return $btn;
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
}
