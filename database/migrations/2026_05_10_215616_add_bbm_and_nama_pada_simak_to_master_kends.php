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
            $table->string('bbm')->nullable()->after('tahun');
            $table->string('nama_pada_simak')->default('-')->after('bbm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_kends', function (Blueprint $table) {
            $table->dropColumn(['bbm', 'nama_pada_simak']);
        });
    }
};
