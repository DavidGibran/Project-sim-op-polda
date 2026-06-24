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
        Schema::table('tb_laporan_kerusakans', function (Blueprint $table) {
            $table->string('nomor_hp')->nullable()->change();
            $table->unsignedBigInteger('id_penugasan')->nullable()->change();
            $table->text('detail_teknis')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_laporan_kerusakans', function (Blueprint $table) {
            $table->string('nomor_hp')->nullable(false)->change();
            $table->unsignedBigInteger('id_penugasan')->nullable(false)->change();
            $table->text('detail_teknis')->nullable(false)->change();
        });
    }

};
