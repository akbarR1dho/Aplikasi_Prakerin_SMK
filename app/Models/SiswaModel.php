<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function akun()
    {
        return $this->hasOne(User::class, 'id_akun');
    }

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }
}
