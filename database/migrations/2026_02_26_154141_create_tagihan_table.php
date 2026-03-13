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
        Schema::create('tagihan', function (Blueprint $table) {
            $table->string('id_t')->primary();
            $table->string('nis');
            $table->foreign('nis')->references('nis')->on('core')->cascadeOnDelete();
            $table->string('id_so');
            $table->foreign('id_so')->references('id_so')->on('so')->cascadeOnUpdate();
            $table->date('tgl_terbit');
            $table->string('nama_tagihan');
            $table->integer('kekurangan_tagihan');
            $table->integer('total_tagihan');
            $table->string('status_tagihan');


            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
