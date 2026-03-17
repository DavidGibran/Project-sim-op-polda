<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify status column type to include 'Diterbitkan'
        DB::statement("ALTER TABLE master_kends MODIFY COLUMN status ENUM('Tersedia', 'Dipakai', 'Perbaikan', 'Diterbitkan') DEFAULT 'Tersedia'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status column type (be careful with existing 'Diterbitkan' records if any)
        DB::statement("ALTER TABLE master_kends MODIFY COLUMN status ENUM('Tersedia', 'Dipakai', 'Perbaikan') DEFAULT 'Tersedia'");
    }
};
