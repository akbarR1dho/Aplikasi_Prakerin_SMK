<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RoleGuruModel extends Model
{
    //
    use HasUuids;

    protected $table = 'role_guru';

    protected $fillable = [
        'id_role',
        'id_guru',
    ];

    public function role()
    {
        return $this->belongsTo(RolesModel::class, 'id_role');
    }

    public function guru()
    {
        return $this->belongsTo(GuruModel::class, 'id_guru');
    }
}
