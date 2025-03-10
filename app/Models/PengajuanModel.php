<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PengajuanModel extends Model
{
    use HasUuids;

    protected $table = 'pengajuan';

    protected $fillable = [
        'status',
        'persetujuan_tu',
        'persetujuan_kaprg',
        'persetujuan_walas',
        'nama_industri',
        'kontak_industri',
        'alamat_industri',
        'id_siswa',
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaModel::class, 'id_siswa');
    }
}
