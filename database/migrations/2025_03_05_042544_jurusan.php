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
        Schema::create('jurusan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama', 100)->unique();
            $table->string('kode_jurusan', 5)->unique();
            $table->foreignUuid('id_kaprog')->nullable(true)->unique()->constrained('guru')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('jurusan');
    }
};
