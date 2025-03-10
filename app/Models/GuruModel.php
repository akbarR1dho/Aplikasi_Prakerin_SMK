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
        return $this->hasOne(User::class, 'id', 'id_akun');
    }

    public function roles()
    {
        return $this->belongsToMany(RolesModel::class, 'role_guru', 'id_guru', 'id_role')->using(RoleGuruModel::class);
    }
}
