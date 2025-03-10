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
        Schema::create('role_guru', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('id_role')->constrained('roles')->onDelete('cascade');
            $table->foreignUuid('id_guru')->constrained('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('role_guru');
    }
};
