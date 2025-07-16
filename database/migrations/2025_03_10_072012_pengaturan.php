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
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique()->nullable(false);
            $table->string('value', 255)->nullable(false);
            $table->timestamps();
        });

        DB::table('pengaturan')->insert([
            ['key' => 'app_name', 'value' => 'Aplikasi Prakerin', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_icon', 'value' => 'icon/default.jpg', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_default_password', 'value' => 'password#1234', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('pengaturan');
    }
};
