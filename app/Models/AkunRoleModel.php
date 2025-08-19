<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AkunRoleModel extends Pivot
{
    //
    use HasUuids;

    protected $table = 'role_guru';

    protected $fillable = [
        'id_role',
        'id_guru',
    ];
}
