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
        return $this->belongsTo(User::class, 'id_akun');
    }

    public function kelas()
    {
        return $this->belongsTo(KelasModel::class, 'id_kelas');
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanModel::class, 'id_siswa');
    }

    public function prakerin()
    {
        return $this->hasMany(PrakerinModel::class, 'id_siswa');
    }
}
