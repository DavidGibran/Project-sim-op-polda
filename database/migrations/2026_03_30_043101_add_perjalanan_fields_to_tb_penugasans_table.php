<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan field pendukung flow pengemudi:
     * - waktu_mulai     : waktu aktual perjalanan dimulai
     * - waktu_selesai   : waktu aktual perjalanan diselesaikan
     * - foto_odometer   : path file foto odometer
     */
    public function up(): void
    {
        Schema::table('tb_penugasans', function (Blueprint $table) {
            /**
             * Waktu mulai perjalanan
             *
             * Nullable karena tugas lama mungkin belum punya data ini.
             */
            $table->dateTime('waktu_mulai')
                ->nullable()
                ->after('tgl_selesai');

            /**
             * Waktu selesai perjalanan
             *
             * Dipakai saat pengemudi benar-benar menekan tombol selesai.
             */
            $table->dateTime('waktu_selesai')
                ->nullable()
                ->after('waktu_mulai');

            /**
             * Path file foto odometer
             *
             * Akan berisi contoh:
             * odometer/abc123.jpg
             */
            $table->string('foto_odometer')
                ->nullable()
                ->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_penugasans', function (Blueprint $table) {
            $table->dropColumn([
                'waktu_mulai',
                'waktu_selesai',
                'foto_odometer',
            ]);
        });
    }
};