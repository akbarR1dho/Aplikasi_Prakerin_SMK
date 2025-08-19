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
        Schema::create('akun_role', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_role')->constrained('role')->onDelete('cascade');
            $table->foreignUuid('id_akun')->constrained('akun')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('akun_role');
    }
};
