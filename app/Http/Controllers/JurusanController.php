<?php

namespace App\Http\Controllers;

use App\Models\GuruModel;
use App\Models\JurusanModel;
use App\Models\RolesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JurusanController extends Controller
{
    //
    public function getGuru()
    {
        // Ambil ID role kaprog
        $roleKaprog = RolesModel::where('nama', 'kaprog')->first();

        // Ambil guru yang belum memiliki role kaprog
        $guruBelumKaprog = GuruModel::whereDoesntHave('roles', function ($query) use ($roleKaprog) {
            $query->where('roles.id', $roleKaprog->id);
        })->select('id', 'nama')->get();

        return response()->json($guruBelumKaprog);
    }

    public function simpan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
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
                    $guru_lama = GuruModel::find($jurusan->getOriginal('kaprog'));
                    $guru_lama->roles()->detach($roleKaprog->id);
                }

                $jurusan->update([
                    'nama' => $request->nama,
                    'kaprog' => $request->kaprog,
                ]);

                $guru_baru = GuruModel::find($request->kaprog);
                $guru_baru->roles()->attach($roleKaprog->id);

                return response()->json(['message' => 'Jurusan berhasil diperbarui']);
            } else {
                $jurusan = JurusanModel::create([
                    'nama' => $request->nama,
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

    public function delete($id)
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
        $jurusan = JurusanModel::find($id);

        // Ambil ID role kaprog
        $roleKaprog = RolesModel::where('nama', 'kaprog')->first();

        // Ambil guru yang belum memiliki role kaprog
        $guruBelumKaprog = GuruModel::whereDoesntHave('roles', function ($query) use ($roleKaprog) {
            $query->where('roles.id', $roleKaprog->id);
        })->select('id', 'nama')->get();

        $jurusan->guru = $guruBelumKaprog;

        return response()->json($jurusan);
    }
}
