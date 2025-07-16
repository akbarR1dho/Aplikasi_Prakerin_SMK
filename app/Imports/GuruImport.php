<?php

namespace App\Imports;

use App\Models\GuruModel;
use App\Models\PengaturanModel;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeImport;
use Illuminate\Support\Str;


class GuruImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation, WithEvents
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    use Importable, SkipsFailures;

    public function rules(): array
    {
        return [
            'nip' => ['required', 'unique:guru,nip'],
            'nama' => 'required',
            'email' => ['required', 'email', 'unique:guru,email'],
            'no_telp' => 'required',
            'jenis_kelamin' => 'required',
        ];
    }

    public $totalRows = 0;

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                $rows = $event->reader->getTotalRows();
                $this->totalRows = max(reset($rows) - 1, 0);
            }
        ];
    }

    public function model(array $row)
    {
        $namaNormalisasi = preg_replace('/\s+/', ' ', trim($row['nama']));

        // Daftar gelar umum (case insensitive)
        $gelar = ['dr', 'dr.', 'dokter', 'prof', 'prof.', 'hj', 'hj.', 'haji', 'ir', 'ir.'];

        // Pisahkan nama menjadi array kata
        $partNama = explode(' ', $namaNormalisasi);

        // Cek jika kata pertama adalah gelar
        $kataAwal = strtolower($partNama[0]);
        if (in_array($kataAwal, $gelar)) {
            // Hapus kata pertama (gelar)
            array_shift($partNama);
        }

        $akun = User::create([
            'username' => implode(' ', $partNama) . substr(Str::uuid(), 0, 4),
            'password' => PengaturanModel::get('app_default_password'),
            'role' => 'guru',
        ]);

        return new GuruModel([
            'nip' => $row['nip'],
            'nama' => $row['nama'],
            'email' => $row['email'],
            'no_telp' => $row['no_telp'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'id_akun' => $akun->id
        ]);
    }
}
