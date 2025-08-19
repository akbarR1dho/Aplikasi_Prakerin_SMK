<?php

namespace App\Imports;

use App\Models\GuruModel;
use App\Models\PengaturanModel;
use App\Models\RoleModel;
use App\Models\User;
use App\Services\NormalisasiNamaService;
use Illuminate\Support\Facades\DB;
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
            'email' => ['required', 'email', 'unique:akun,email'],
            'no_telp' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
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
            ]);
            $role = RoleModel::where('nama', 'guru')->first();
            $akun->role()->attach($role->id);

            return new GuruModel([
                'nip' => $row['nip'],
                'nama' => $row['nama'],
                'no_telp' => $row['no_telp'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'id_akun' => $akun->id
            ]);
        });
    }
}
