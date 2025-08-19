<?php

namespace App\Services;

use Illuminate\Support\Arr;

class PrioritasRoleService
{
    protected array $priority = [
        'hubin' => 100,
        'kaprog' => 80,
        'walas' => 60,
        'tu' => 50,
        'pembimbing_sekolah' => 40,
        'siswa' => 10,
        'guest' => 0
    ];

    public function roleUtama(array $roles): string
    {
        // Urutkan berdasarkan priority
        $sorted = Arr::sort($roles, fn($role) => $this->priority[$role] ?? 0);
        return Arr::last($sorted) ?: 'guest';
    }

    public function rolePrioritasLebihTinggi(string $role, string $comparedTo): bool
    {
        return ($this->priority[$role] ?? 0) > ($this->priority[$comparedTo] ?? 0);
    }
}
