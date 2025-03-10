<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TuModel extends Model
{
    //
    use HasUuids;

    protected $table = 'tu';

    protected $fillable = [
        'nama',
        'email',
        'no_telp',
        'jenis_kelamin',
        'id_akun',
    ];

    public function akun()
    {
        return $this->hasOne(User::class, 'id_akun');
    }
}
