<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PrakerinModel extends Model
{
    use HasUuids;

    protected $table = 'prakerin';

    protected $fillable = [
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'bukti',
        'id_siswa',
        'id_pengajuan',
        'pembimbing_sekolah',
        'pembimbing_industri',
    ];

    public function siswa()
    {
        return $this->belongsTo(SiswaModel::class, 'id_siswa');
    }

    public function pengajuan()
    {
        return $this->hasOne(PengajuanModel::class, 'id', 'id_pengajuan');
    }

    public function pembimbing_sekolah()
    {
        return $this->belongsTo(GuruModel::class, 'pembimbing_sekolah');
    }
}
