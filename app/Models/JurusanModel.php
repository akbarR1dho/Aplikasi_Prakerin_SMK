<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class JurusanModel extends Model
{
    //
    use HasUuids;

    protected $table = 'jurusan';

    protected $fillable = [
        'nama',
        'kaprog',
    ];

    public function kelas()
    {
        return $this->hasMany(KelasModel::class, 'id_jurusan');
    }

    public function kaprog()
    {
        return $this->hasOne(GuruModel::class, 'id', 'kaprog');
    }
}
