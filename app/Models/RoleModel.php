<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    //
    use HasUuids;

    protected $table = 'role';
    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    public function akun()
    {
        return $this->belongsToMany(User::class, 'akun_role', 'id_role', 'id_akun')->using(AkunRoleModel::class);
    }
}
