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
        'no_telp',
        'jenis_kelamin',
        'id_akun',
    ];

    public function akun()
    {
        return $this->belongsTo(User::class, 'id_akun');
    }
}
