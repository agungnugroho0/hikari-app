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
        Schema::create('staff', function (Blueprint $table) {
            $table->string('id_staff', 20)->primary();
            $table->string('nama_s');
            $table->string('username')->unique();
            $table->enum('akses', ['admin', 'guru', 'dev']);
            $table->string('foto_s')->nullable();
            $table->string('password');
            $table->string('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
