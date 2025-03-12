<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class KelasModel extends Model
{
    //
    use HasUuids;

    protected $table = 'kelas';

    protected $fillable = [
        'angkatan',
        'tingkat',
        'kelompok',
        'id_jurusan',
        'walas',
    ];

    public function jurusan()
    {
        return $this->belongsTo(JurusanModel::class, 'id_jurusan');
    }

    public function siswa()
    {
        return $this->hasMany(SiswaModel::class, 'id_kelas');
    }

    public function walas()
    {
        return $this->belongsTo(GuruModel::class, 'walas');
    }
}
