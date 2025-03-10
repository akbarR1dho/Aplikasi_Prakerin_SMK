<?php

namespace Database\Seeders;

use App\Models\GuruModel;
use App\Models\HubinModel;
use App\Models\RoleGuruModel;
use App\Models\RolesModel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $hubin = User::create([
            'username' => 'hubin',
            'password' => 'hubin#1234',
            'role' => 'hubin'
        ]);

        HubinModel::create([
            'nip' => '1234567890',
            'email' => 'hubin@example.com',
            'nama' => 'Hubin',
            'no_telp' => '0869696969',
            'jenis_kelamin' => 'L',
            'id_akun' => $hubin->id
        ]);

        RolesModel::create([
            'nama' => 'walas'
        ]);

        RolesModel::create([
            'nama' => 'kaprog'
        ]);

        RolesModel::create([
            'nama' => 'pembimbing_sekolah'
        ]);
    }
}
