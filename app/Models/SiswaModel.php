<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SiswaModel extends Model
{
    //
    protected $table = 'siswa';

    // Primary key
    protected $primaryKey = 'nis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nis',
        'nama',
        'no_telp',
        'jenis_kelamin',
        'email',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'tahun_masuk',
        'alamat',
        'id_kelas',
        'id_akun',
    ];

    // protected static function booted()
    // {
    //     static::updating(function ($model) {
    //         if ($model->isDirty('nama')) {
    //             $namaAwal = explode(' ', trim($model->nama))[0];

    //             $akun = User::where('id_akun', $model->id_akun)->first();

    //             if ($akun) {
    //                 $akun->update([
    //                     'username' => $namaAwal . substr(Str::uuid(), 0, 4),
    //                 ]);
    //             }
    //         }
    //     });
    // }

    public function akun()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }
}
