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
        // create tabel user
        Schema::create('akun', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username', 100)->unique()->nullable(false);
            $table->string('password', 255)->nullable(false);
            $table->enum('role', ['siswa', 'hubin', 'guru']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('user');
    }
};
