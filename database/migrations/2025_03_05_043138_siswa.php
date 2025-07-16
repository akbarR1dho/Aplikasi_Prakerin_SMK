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
            $table->string('nis', 20)->primary();
            $table->string('nama', 100)->index();
            $table->string('nisn', 20)->unique();
            $table->string('no_telp', 20);
            $table->string('alamat', 255);
            $table->string('tempat_lahir', 30);
            $table->date('tanggal_lahir');
            $table->smallInteger('tahun_masuk', false, true);
            $table->enum('jenis_kelamin', ['P', 'L']);
            $table->foreignUuid('id_akun')->unique()->constrained('akun')->onDelete('cascade');
            $table->foreignUuid('id_kelas')->nullable(true)->constrained('kelas')->nullOnDelete();
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
