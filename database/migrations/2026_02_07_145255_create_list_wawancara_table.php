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
        Schema::create('list_wawancara', function (Blueprint $table) {
            $table->string('id_list')->primary();
            $table->string('nis');
            $table->foreign('nis')->references('nis')->on('core')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('id_job');
            $table->foreign('id_job')->references('id_job')->on('job_fair')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_wawancara');
    }
};
