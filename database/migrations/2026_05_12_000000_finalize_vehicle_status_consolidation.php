<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /**
         * 1. Allow temporary statuses to avoid constraint errors during mapping
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
         * 2. Map existing legacy statuses to consolidated ones
         */
        // Diterbitkan -> Tersedia (Assignment exists but vehicle is not yet in use)
        DB::statement("
            UPDATE master_kends
            SET status = 'Tersedia'
            WHERE status = 'Diterbitkan'
        ");

        // Diterima & Perjalanan -> Dipakai
        DB::statement("
            UPDATE master_kends
            SET status = 'Dipakai'
            WHERE status IN ('Diterima', 'Perjalanan')
        ");

        /**
         * 3. Set the final strict ENUM
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add legacy statuses back
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
    }
};
