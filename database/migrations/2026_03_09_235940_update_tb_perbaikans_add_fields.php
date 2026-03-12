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
        Schema::table('tb_perbaikans', function (Blueprint $table) {
            $table->foreignId('id_kend')->constrained('master_kends', 'id_kend')->after('id');
            $table->date('tanggal_lapor')->after('id_kend');
            $table->text('keluhan')->after('tanggal_lapor');
            $table->enum('status', ['dilaporkan', 'diproses', 'selesai'])->default('dilaporkan')->after('keluhan');
            $table->string('teknisi')->nullable()->after('status');
            $table->timestamp('tgl_mulai')->nullable()->after('teknisi');
            $table->timestamp('tgl_selesai')->nullable()->after('tgl_mulai');
            $table->integer('biaya')->nullable()->after('tgl_selesai');
            $table->text('catatan')->nullable()->after('biaya');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_perbaikans', function (Blueprint $table) {
            $table->dropForeign(['id_kend']);
            $table->dropColumn([
                'id_kend', 'tanggal_lapor', 'keluhan', 'status', 
                'teknisi', 'tgl_mulai', 'tgl_selesai', 'biaya', 'catatan'
            ]);
        });
    }
};
