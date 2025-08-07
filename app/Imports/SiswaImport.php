<?php

namespace App\Imports;

use App\Models\KelasModel;
use App\Models\PengaturanModel;
use App\Models\SiswaModel;
use App\Models\User;
use App\Services\NormalisasiNamaService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeImport;


class SiswaImport implements ToModel, WithHeadingRow, SkipsOnFailure, WithValidation, WithEvents, SkipsEmptyRows
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
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:akun,email',
            'nisn' => 'required|unique:siswa,nisn|digits:10',
            'nis' => 'required|unique:siswa,nis',
            'jenis_kelamin' => 'required|in:L,P',
            'no_telp' => 'required|string|regex:/^[0-9\+]+$/',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required',
            'tahun_masuk' => 'required|integer|digits:4',
            'alamat' => 'required|string|max:255',
            'id_kelas' => 'required|exists:kelas,id_kelas',
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
        $normalisasiNama = new NormalisasiNamaService();
        $generateUsername = $normalisasiNama->generateUsername($row['nama']);

        DB::transaction(function () use ($generateUsername, $row) {
            $akun = User::create([
                'username' => $generateUsername,
                'password' => PengaturanModel::get('app_default_password'),
                'email' => $row['email'],
                'role' => 'siswa',
            ]);

            $kelas = KelasModel::where('id_kelas', $row['id_kelas'])->select('id', 'id_kelas')->first();

            SiswaModel::create([
                'id_akun' => $akun->id,
                'nama' => $row['nama'],
                'nisn' => $row['nisn'],
                'nis' => $row['nis'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'no_telp' => $row['no_telp'],
                'tempat_lahir' => $row['tempat_lahir'],
                'tanggal_lahir' => $row['tanggal_lahir'],
                'tahun_masuk' => $row['tahun_masuk'],
                'alamat' => $row['alamat'],
                'id_kelas' => $kelas->id,
            ]);
        });

        // dd($row);
    }
}
