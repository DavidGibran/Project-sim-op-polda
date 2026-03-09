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
        Schema::create('master_kends', function (Blueprint $table) {
            $table->id('id_kend');
            $table->string('no_polisi')->unique();
            $table->enum('kategori_kend', ['RANUM','RANSUS']);
            $table->string('jenis_kend');
            $table->string('merk');
            $table->year('tahun');
            $table->unsignedInteger('km_terakhir')->default(0);
            $table->enum('status', ['Tersedia','Dipakai','Perbaikan'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kends');
    }
};
