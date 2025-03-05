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
        Schema::create('kelas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('angkatan', 5);
            $table->enum('tingkat', ['10', '11', '12']);
            $table->enum('kelompok', ['A', 'B', 'C']);
            $table->foreignUuid('id_jurusan')->constrained('jurusan')->onDelete('cascade');
            $table->foreignUuid('walas')->constrained('guru')->nullOnDelete()->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('kelas');
    }
};
