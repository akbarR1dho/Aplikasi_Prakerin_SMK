<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RolesModel extends Model
{
    //
    use HasUuids;

    protected $table = 'roles';
    public $timestamps = false;

    protected $fillable = [
        'nama',
    ];

    public function gurus()
    {
        return $this->belongsToMany(GuruModel::class, 'role_guru', 'id_role', 'id_guru')->using(RoleGuruModel::class);
    }
}
