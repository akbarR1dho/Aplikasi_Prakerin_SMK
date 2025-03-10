<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class HubinModel extends Model
{
    use HasUuids;

    protected $table = 'hubin';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_telp',
        'jenis_kelamin',
        'id_akun',
    ];

    public function akun()
    {
        return $this->hasOne(User::class, 'id', 'id_akun');
    }
}
