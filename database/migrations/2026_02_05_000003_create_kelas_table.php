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
        Schema::dropIfExists('kelas');

        Schema::create('kelas', function (Blueprint $table) {
            $table->string('id_kelas', 20)->primary();
            $table->string('nama_kelas');
            $table->integer('tingkat')->nullable();
            $table->string('id_pengajar', 20)->nullable();
            $table->foreign('id_pengajar')->references('id_staff')->on('staff')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
