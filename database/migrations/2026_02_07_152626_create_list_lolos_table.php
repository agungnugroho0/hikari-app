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
        Schema::create('list_lolos', function (Blueprint $table) {
            $table->string('id_lolos')->primary();
            $table->string('nis');
            $table->foreign('nis')->references('nis')->on('core')->cascadeOnUpdate();
            $table->string('id_so');
            $table->foreign('id_so')->references('id_so')->on('so')->cascadeOnUpdate();
            $table->date('tgl_lolos');
            $table->string('nama_job');
            $table->string('nama_perusahaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_lolos');
    }
};
