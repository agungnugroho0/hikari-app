<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_login', function (Blueprint $table) {
            $table->id('id_log');
            $table->string('id_staff', 20)->unique();
            $table->dateTime('last_login');
            $table->foreign('id_staff')->references('id_staff')->on('staff')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_login');
    }
};
