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
        Schema::create('prakerin', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('status', ['pending', 'berjalan', 'selesai', 'berhenti']);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('bukti', 200);
            $table->string('id_siswa', 30);
            $table->foreign('id_siswa')->references('nis')->on('siswa')->onDelete('cascade');
            $table->foreignUuid('id_pengajuan')->unique()->constrained('pengajuan')->nullOnDelete();
            $table->foreignUuid('pembimbing_sekolah')->constrained('guru')->nullOnDelete();
            $table->foreignUuid('pembimbing_industri')->constrained('pembimbing_industri')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
