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
        Schema::create('detail_siswa', function (Blueprint $table) {
            $table->string('nis')->primary();
            $table->foreign('nis')->references('nis')->on('core')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->string('panggilan');
            $table->date('tgl_lahir');
            $table->enum('gender', ['L', 'P']);
            $table->string('tempat_lhr', '100');
            $table->text('alamat');
            $table->string('wa');
            $table->string('wa_wali')->nullable();
            $table->string('pernikahan');
            $table->string('agama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_siswa');
    }
};
