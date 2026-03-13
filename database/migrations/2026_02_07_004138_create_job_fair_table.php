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
        Schema::create('job_fair', function (Blueprint $table) {
            $table->string('id_job')->primary();
            $table->string('nama_job');
            $table->string('perusahaan');
            $table->string('id_so');
            $table->date('tgl_wawancara')->nullable();
            $table->string('penempatan')->nullable();
            $table->string('metode');
            $table->foreign('id_so')->references('id_so')->on('so');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_fairs');
    }
};
