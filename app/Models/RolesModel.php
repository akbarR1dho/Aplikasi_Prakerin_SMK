<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model
{
    //
    use HasUuids;

    protected $table = 'roles';

    protected $fillable = [
        'nama',
    ];

    public function role_guru()
    {
        return $this->hasMany(RoleGuruModel::class);
    }
}
