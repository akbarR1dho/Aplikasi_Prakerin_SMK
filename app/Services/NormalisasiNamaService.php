<?php

namespace App\Services;

use Illuminate\Support\Str;

class NormalisasiNamaService
{
    public function hapusGelarDepan(string $nama): string
    {
        // Pattern untuk menghapus semua gelar berurutan di depan
        $namaTanpaGelar = preg_replace('/^(?:\w{1,4}\.\s+)+(?=[A-Z])/i', '', trim($nama));

        // Ambil kata pertama setelah gelar
        $namaPertama = explode(' ', $namaTanpaGelar)[0];

        // Validasi hasil
        return ctype_upper($namaPertama[0]) ? strtolower($namaPertama) : 'user';
    }

    public function generateUsername(string $nama): string
    {
        $namaPertama = $this->hapusGelarDepan($nama);
        return $namaPertama . substr(Str::uuid(), 0, 4);
    }
}
