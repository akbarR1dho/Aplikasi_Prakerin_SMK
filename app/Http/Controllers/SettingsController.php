<?php

namespace App\Http\Controllers;

use App\Models\SettingsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    //
    public function index()
    {
        return view('dashboard.settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'app_default_password' => 'required|string|min:6|max:20',
        ]);

        SettingsModel::set('app_name', $request->app_name);
        SettingsModel::set('app_default_password', $request->app_default_password);


        if ($request->hasFile('app_icon')) {
            if (SettingsModel::get('app_icon') != null) {
                Storage::disk('public')->delete(str_replace('storage/', '', SettingsModel::get('app_icon')));
            }

            $iconPath = Storage::disk('public')->putFile('icon', $request->file('app_icon'));

            SettingsModel::set('app_icon', 'storage/' . $iconPath);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
