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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->string('id_tx')->primary();
            $table->string('nis');
            $table->foreign('nis')->references('nis')->on('core')->cascadeOnUpdate();
            $table->string('id_t');
            $table->foreign('id_t')->references('id_t')->on('tagihan')->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->date('tgl_transaksi');
            $table->string('nama_transaksi');
            $table->integer('nominal');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
