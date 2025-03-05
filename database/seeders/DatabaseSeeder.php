<?php

namespace Database\Seeders;

use App\Models\GuruModel;
use App\Models\HubinModel;
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
        // User::factory(10)->create();

        $hubin = User::create([
            'username' => 'test hubin',
            'password' => 'hubin#1234',
            'role' => 'hubin'
        ]);

        $guru = User::create([
            'username' => 'test guru',
            'password' => 'guru#1234',
            'role' => 'guru'
        ]);

        GuruModel::create([
            'nip' => '1234567890',
            'email' => 'test_guru@example.com',
            'nama' => 'AkbarCihuy',
            'no_telp' => '0869696969',
            'jenis_kelamin' => 'L',
            'id_akun' => $guru->id
        ]);

        HubinModel::create([
            'nip' => '1234567890',
            'email' => 'test_hubin@example.com',
            'nama' => 'AkbarCihuy',
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
