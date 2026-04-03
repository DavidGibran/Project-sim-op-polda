<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * ============================================
         * 1. tb_penugasans: tambahkan status baru
         * ============================================
         */
        DB::statement("
            ALTER TABLE tb_penugasans
            MODIFY status ENUM(
                'diterbitkan',
                'diterima',
                'berjalan',
                'selesai',
                'dibatalkan'
            ) DEFAULT 'diterbitkan'
        ");

        /**
         * ============================================
         * 2. master_kends:
         * ubah enum sementara agar menerima
         * status lama + status baru
         * ============================================
         *
         * Kenapa perlu ini?
         * Karena sekarang data lama masih punya 'Dipakai',
         * dan kita ingin mengubahnya ke 'Perjalanan'.
         * Jadi kolom harus sementara mengizinkan keduanya.
         */
        DB::statement("
            ALTER TABLE master_kends
            MODIFY status ENUM(
                'Tersedia',
                'Dipakai',
                'Diterbitkan',
                'Diterima',
                'Perjalanan',
                'Perbaikan'
            ) DEFAULT 'Tersedia'
        ");

        /**
         * ============================================
         * 3. Mapping data lama
         * Dipakai -> Perjalanan
         * ============================================
         */
        DB::statement("
            UPDATE master_kends
            SET status = 'Perjalanan'
            WHERE status = 'Dipakai'
        ");

        /**
         * ============================================
         * 4. Finalisasi enum master_kends
         * hapus status lama 'Dipakai'
         * ============================================
         */
        DB::statement("
            ALTER TABLE master_kends
            MODIFY status ENUM(
                'Tersedia',
                'Diterbitkan',
                'Diterima',
                'Perjalanan',
                'Perbaikan'
            ) DEFAULT 'Tersedia'
        ");
    }

    public function down(): void
    {
        /**
         * Kembalikan enum sementara
         * agar bisa mapping balik
         */
        DB::statement("
            ALTER TABLE master_kends
            MODIFY status ENUM(
                'Tersedia',
                'Dipakai',
                'Diterbitkan',
                'Diterima',
                'Perjalanan',
                'Perbaikan'
            ) DEFAULT 'Tersedia'
        ");

        /**
         * Mapping balik
         * Perjalanan -> Dipakai
         */
        DB::statement("
            UPDATE master_kends
            SET status = 'Dipakai'
            WHERE status = 'Perjalanan'
        ");

        /**
         * Rollback tb_penugasans
         */
        DB::statement("
            ALTER TABLE tb_penugasans
            MODIFY status ENUM(
                'diterbitkan',
                'berjalan',
                'selesai'
            ) DEFAULT 'diterbitkan'
        ");

        /**
         * Rollback final master_kends
         */
        DB::statement("
            ALTER TABLE master_kends
            MODIFY status ENUM(
                'Tersedia',
                'Dipakai',
                'Perbaikan'
            ) DEFAULT 'Tersedia'
        ");
    }
};