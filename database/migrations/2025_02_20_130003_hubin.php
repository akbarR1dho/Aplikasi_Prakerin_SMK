<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('hubin', function (Blueprint $table) {
            $table->string('nip', 20)->primary();
            $table->string('nama', 50);
            $table->string('no_telp', 22);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->foreignUuid('id_user')->constrained('akun')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('hubin');
    }
};
