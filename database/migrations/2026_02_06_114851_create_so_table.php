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
            $table->string('lokasi');
            $table->string('pj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so');
    }
};
