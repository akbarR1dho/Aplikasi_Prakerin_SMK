<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['pending', 'disetujui', 'ditolak']);
            $table->enum('persetujuan_tu', ['setuju', 'tolak']);
            $table->enum('persetujuan_walas', ['setuju', 'tolak']);
            $table->enum('persetujuan_kaprog', ['setuju', 'tolak']);
            $table->string('nama_industri', 100);
            $table->string('kontak_industri', 150);
            $table->string('alamt_industri', 255);
            $table->string('id_siswa', 30);
            $table->foreign('id_siswa')->references('nis')->on('siswa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('pengajuan');
    }
};
