<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan field log pemakaian ke tabel tb_logs
     * tanpa menghapus field log lama.
     */
    public function up(): void
    {
        Schema::table('tb_logs', function (Blueprint $table) {
            /**
             * Relasi ke penugasan dan kendaraan
             *
             * Nullable agar log lama tetap aman.
             */
            $table->unsignedBigInteger('penugasan_id')->nullable()->after('id_log');
            $table->unsignedBigInteger('id_kend')->nullable()->after('penugasan_id');

            /**
             * Kode tugas
             * Contoh: L1234AB-12-03-2026
             */
            $table->string('kode_tugas')->nullable()->after('id_kend');

            /**
             * Snapshot data perjalanan
             * Disimpan di log agar historinya tetap utuh
             * walaupun data master berubah.
             */
            $table->date('tanggal_tugas')->nullable()->after('kode_tugas');
            $table->string('nama_pengemudi')->nullable()->after('tanggal_tugas');
            $table->string('nopol')->nullable()->after('nama_pengemudi');
            $table->string('jenis_kendaraan')->nullable()->after('nopol');
            $table->string('tipe_kendaraan')->nullable()->after('jenis_kendaraan');
            $table->string('tujuan')->nullable()->after('tipe_kendaraan');

            /**
             * Data kilometer
             */
            $table->integer('km_awal')->nullable()->after('tujuan');
            $table->integer('km_akhir')->nullable()->after('km_awal');

            /**
             * Path file foto odometer
             * Contoh: odometer/abc123.jpg
             */
            $table->string('foto_odometer')->nullable()->after('km_akhir');

            /**
             * Waktu mulai dan selesai perjalanan
             */
            $table->dateTime('waktu_mulai')->nullable()->after('foto_odometer');
            $table->dateTime('waktu_selesai')->nullable()->after('waktu_mulai');

            /**
             * Catatan tambahan dari pengemudi
             */
            $table->string('catatan', 255)->nullable()->after('waktu_selesai');

            /**
             * Index untuk mempercepat query log pemakaian
             */
            $table->index('penugasan_id');
            $table->index('id_kend');
            $table->index('kode_tugas');
            $table->index('tanggal_tugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_logs', function (Blueprint $table) {
            $table->dropIndex(['penugasan_id']);
            $table->dropIndex(['id_kend']);
            $table->dropIndex(['kode_tugas']);
            $table->dropIndex(['tanggal_tugas']);

            $table->dropColumn([
                'penugasan_id',
                'id_kend',
                'kode_tugas',
                'tanggal_tugas',
                'nama_pengemudi',
                'nopol',
                'jenis_kendaraan',
                'tipe_kendaraan',
                'tujuan',
                'km_awal',
                'km_akhir',
                'foto_odometer',
                'waktu_mulai',
                'waktu_selesai',
                'catatan',
            ]);
        });
    }
};