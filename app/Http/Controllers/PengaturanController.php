<?php

namespace App\Http\Controllers;

use App\Models\PengaturanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    //
    public function index()
    {
        return view('dashboard.pengaturan');
    }

    public function edit(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'app_default_password' => 'required|string|min:6|max:20',
        ]);

        PengaturanModel::set('app_name', $request->app_name);
        PengaturanModel::set('app_default_password', $request->app_default_password);


        if ($request->hasFile('app_icon')) {
            if (PengaturanModel::get('app_icon') != null) {
                Storage::disk('public')->delete(str_replace('storage/', '', PengaturanModel::get('app_icon')));
            }

            $iconPath = Storage::disk('public')->putFile('icon', $request->file('app_icon'));

            PengaturanModel::set('app_icon', 'storage/' . $iconPath);
        }

        return redirect()->route('pengaturan.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
