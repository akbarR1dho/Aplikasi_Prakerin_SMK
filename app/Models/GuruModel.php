<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GuruModel extends Model
{
    //
    use HasUuids;

    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nama',
        'no_telp',
        'jenis_kelamin',
        'id_akun',
    ];

    public function akun()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }

    public function kelas()
    {
        return $this->hasMany(KelasModel::class, 'id_walas');
    }
}
