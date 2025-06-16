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
        Schema::create('guru', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nip', 20)->unique()->nullable(true);
            $table->string('email', 150)->unique()->nullable(false);
            $table->string('nama', 100)->index();
            $table->string('no_telp', 20);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->foreignUuid('id_akun')->unique()->constrained('akun')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('guru');
    }
};
