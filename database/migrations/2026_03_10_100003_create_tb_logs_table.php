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
        Schema::create('tb_logs', function (Blueprint $table) {
            $table->id('id_log');
            $table->foreignId('id_user')->constrained('users', 'id');
            $table->string('aksi');
            $table->string('modul');
            $table->text('deskripsi');
            $table->string('ip_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_logs');
    }
};
