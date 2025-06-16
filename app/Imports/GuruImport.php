<?php

namespace App\Imports;

use App\Models\GuruModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Validators\Failure;

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
        return new GuruModel([
            'nip' => $row['nip'],
            'nama' => $row['nama'],
            'email' => $row['email'],
            'no_telp' => $row['no_telp'],
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }

    // public function onFailure(Failure ...$failures)
    // {
    //     foreach ($failures as $failure) {
    //         $failure->row(); // row that went wrong
    //         $failure->attribute(); // either heading key (if using heading row concern) or column index
    //         $failure->errors(); // actual error messages from Laravel validator
    //         $failure->values(); // the values of the row that has the error
    //     }
    // }
}
