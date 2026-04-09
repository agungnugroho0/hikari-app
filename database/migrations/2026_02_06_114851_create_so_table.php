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
        Schema::create('so', function (Blueprint $table) {
            $table->string('id_so')->primary();
            $table->string('nama_so');
            $table->string('foto_so');
            $table->string('lokasi')->nullable();
            $table->string('pj')->nullable();
            $table->string('ket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('tagihan');
        Schema::dropIfExists('list_wawancara');
        Schema::dropIfExists('list_lolos');
        Schema::dropIfExists('job_fair');
        Schema::dropIfExists('so');
    }
};
