<?php

namespace Database\Seeders;

use App\Models\HubinModel;
use App\Models\RolesModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class DBSeed extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $hubin = User::create([
            'username' => 'hubin',
            'email' => 'hubin@example.com',
            'password' => 'hubin#1234',
            'role' => 'hubin'
        ]);

        HubinModel::create([
            'nip' => '1234567890',
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
