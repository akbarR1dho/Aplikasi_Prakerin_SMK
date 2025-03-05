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
        Schema::create('siswa', function (Blueprint $table) {
            $table->string('nis', 30)->primary();
            $table->string('nama', 100);
            $table->string('email', 150)->unique();
            $table->string('nisn', 15)->unique();
            $table->string('no_telp', 20);
            $table->string('alamat', 255);
            $table->string('tempat_lahir', 30);
            $table->date('tanggal_lahir');
            $table->string('tahun_masuk', 5);
            $table->enum('jenis_kelamin', ['P', 'L']);
            $table->foreignUuid('id_akun')->constrained('akun')->onDelete('cascade');
            $table->foreignUuid('id_kelas')->constrained('kelas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('siswa');
    }
};
