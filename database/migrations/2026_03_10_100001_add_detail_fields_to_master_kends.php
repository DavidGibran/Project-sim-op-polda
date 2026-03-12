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
        Schema::table('master_kends', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['kategori_kend', 'jenis_kend', 'km_terakhir']);
            
            // Add new columns
            $table->string('tipe')->after('merk')->nullable();
            $table->string('kategori_kendaraan')->after('tipe')->nullable();
            $table->string('jenis_kendaraan')->after('kategori_kendaraan')->nullable();
            $table->string('keterangan_penggunaan')->after('jenis_kendaraan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_kends', function (Blueprint $table) {
            $table->dropColumn(['tipe', 'kategori_kendaraan', 'jenis_kendaraan', 'keterangan_penggunaan']);
            
            $table->enum('kategori_kend', ['RANUM','RANSUS'])->after('no_polisi')->nullable();
            $table->string('jenis_kend')->after('kategori_kend')->nullable();
            $table->unsignedInteger('km_terakhir')->default(0)->after('tahun');
        });
    }
};
