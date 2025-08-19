<?php

namespace Database\Seeders;

use App\Models\HubinModel;
use App\Models\RoleModel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DBSeed extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat data role
        $roles = [
            'walas',
            'kaprog',
            'pembimbing_sekolah',
            'pembimbing_industri',
            'hubin',
            'tu',
            'siswa'
        ];

        foreach ($roles as $role) {
            RoleModel::create([
                'nama' => $role
            ]);
        }

        // Buat data dan akun hubin
        DB::transaction(function () {
            $hubin = User::create([
                'username' => 'hubin',
                'email' => 'hubin@example.com',
                'password' => 'hubin#1234',
            ]);

            $role = RoleModel::where('nama', 'hubin')->first();
            $hubin->role()->attach($role->id);

            HubinModel::create([
                'nip' => '1234567890',
                'nama' => 'Hubin',
                'no_telp' => '0869696969',
                'jenis_kelamin' => 'L',
                'id_akun' => $hubin->id
            ]);
        });
    }
}
