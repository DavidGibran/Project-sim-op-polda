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
        Schema::table('tb_penugasans', function (Blueprint $table) {
            // Drop unused columns
            $table->dropColumn(['kegiatan', 'prj_mulai', 'prj_selesai', 'foto_odo']);

            // Rename columns
            $table->renameColumn('id_tugas', 'id');
            $table->renameColumn('nama_sopir', 'pengemudi');
            $table->renameColumn('odo_awal', 'km_awal');
            $table->renameColumn('odo_akhir', 'km_akhir');
            $table->renameColumn('note', 'catatan');
            
            // Add tgl_selesai
            $table->date('tgl_selesai')->nullable()->after('tgl_tugas');
        });

        // Modify status column type
        DB::statement("ALTER TABLE tb_penugasans MODIFY status ENUM('diterbitkan', 'berjalan', 'selesai', 'dibatalkan') DEFAULT 'diterbitkan'");
        // Make pengemudi nullable
        DB::statement("ALTER TABLE tb_penugasans MODIFY pengemudi VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_penugasans', function (Blueprint $table) {
            $table->renameColumn('id', 'id_tugas');
            $table->renameColumn('pengemudi', 'nama_sopir');
            $table->renameColumn('km_awal', 'odo_awal');
            $table->renameColumn('km_akhir', 'odo_akhir');
            $table->renameColumn('catatan', 'note');

            $table->dropColumn('tgl_selesai');

            $table->text('kegiatan')->nullable();
            $table->timestamp('prj_mulai')->nullable();
            $table->timestamp('prj_selesai')->nullable();
            $table->string('foto_odo')->nullable();
            // It might complain for id_user without foreign key logic here, so omitting for safety
            $table->foreignId('id_user')->nullable()->constrained('users', 'id');
        });

        DB::statement("ALTER TABLE tb_penugasans MODIFY status ENUM('diterbitkan', 'diterima', 'berjalan', 'selesai') DEFAULT 'diterbitkan'");
        DB::statement("ALTER TABLE tb_penugasans MODIFY nama_sopir VARCHAR(255) NOT NULL");
    }
};
