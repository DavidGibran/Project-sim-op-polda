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
        Schema::create('tb_laporan_kerusakans', function (Blueprint $table) {
            $table->id();
            $table->string('no_laporan')->unique();
            $table->foreignId('id_kend')->constrained('master_kends', 'id_kend');
            $table->foreignId('id_penugasan')->nullable()->constrained('tb_penugasans', 'id');
            $table->enum('sumber', ['admin', 'kendaraan']);
            $table->enum('mode', ['simple', 'detail']);
            $table->text('keluhan');
            $table->text('detail_teknis')->nullable();
            $table->string('nomor_hp');
            $table->enum('status', ['diterbitkan', 'diproses', 'selesai'])->default('diterbitkan');
            $table->timestamp('tanggal_lapor')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_laporan_kerusakans');
    }
};
