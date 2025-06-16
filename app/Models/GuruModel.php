<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($model) {
            // Normalisasi nama
            $namaNormalisasi = preg_replace('/\s+/', ' ', trim($model->nama));

            // Daftar gelar umum (case insensitive)
            $gelar = ['dr', 'dr.', 'dokter', 'prof', 'prof.', 'hj', 'hj.', 'haji', 'ir', 'ir.'];

            // Pisahkan nama menjadi array kata
            $partNama = explode(' ', $namaNormalisasi);

            // Cek jika kata pertama adalah gelar
            $kataAwal = strtolower($partNama[0]);
            if (in_array($kataAwal, $gelar)) {
                // Hapus kata pertama (gelar)
                array_shift($partNama);
            }

            $akun = User::create([
                'username' => implode(' ', $partNama) . substr(Str::uuid(), 0, 4),
                'password' => PengaturanModel::get('app_default_password'),
                'role' => 'guru',
            ]);

            if (!$akun) {
                throw new \Exception('Gagal membuat akun');
            }

            $model->id_akun = $akun->id;
        });

        static::updating(function ($model) {
            if ($model->isDirty('nama')) {
                $namaAwal = explode(' ', trim($model->getOriginal('nama')))[0];
                $akun = $model->akun;

                if ($akun) {
                    $akun->update([
                        'username' => $namaAwal . substr(Str::uuid(), 0, 4),
                    ]);
                }
            }
        });
    }

    public function akun()
    {
        return $this->hasOne(User::class, 'id', 'id_akun');
    }

    public function roles()
    {
        return $this->belongsToMany(RolesModel::class, 'role_guru', 'id_guru', 'id_role')->using(RoleGuruModel::class);
    }

    public function kelas()
    {
        return $this->hasMany(KelasModel::class, 'id_walas');
    }
}
