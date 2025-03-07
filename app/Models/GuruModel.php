<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class GuruModel extends Model
{
    //
    use HasUuids;

    protected $table = 'guru';

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
        return $this->belongsTo(User::class, 'id_akun');
    }

    public function role_guru()
    {
        return $this->hasMany(RoleGuruModel::class);
    }
}
