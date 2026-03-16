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
            $table->integer('km_terakhir')->default(0)->after('jenis_kendaraan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_kends', function (Blueprint $table) {
            $table->dropColumn('km_terakhir');
        });
    }
};
