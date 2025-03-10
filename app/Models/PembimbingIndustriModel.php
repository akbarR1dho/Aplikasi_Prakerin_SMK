<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class PembimbingIndustriModel extends Model
{
    use HasUuids;

    protected $table = 'pembimbing_industri';

    protected $fillable = [
        'email',
        'nama',
        'no_telp',
        'jenis_kelamin',
        'id_akun',
    ];

    public function akun()
    {
        return $this->hasOne(User::class, 'id_akun');
    }
}
