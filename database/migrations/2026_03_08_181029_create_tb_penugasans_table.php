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
        Schema::create('tb_penugasans', function (Blueprint $table) {
            $table->id('id_tugas');
            $table->foreignId('id_kend')
                ->constrained('master_kends','id_kend');

            $table->foreignId('id_user')
                ->constrained('users','id');
                
            $table->string('nama_sopir');
            $table->date('tgl_tugas');
            $table->string('tujuan');
            $table->text('kegiatan');
            $table->integer('odo_awal')->nullable();
            $table->integer('odo_akhir')->nullable();
            $table->timestamp('prj_mulai')->nullable();
            $table->timestamp('prj_selesai')->nullable();
            $table->string('foto_odo')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', [
                'diterbitkan',
                'diterima',
                'berjalan',
                'selesai'
            ])->default('diterbitkan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_penugasans');
    }
};
