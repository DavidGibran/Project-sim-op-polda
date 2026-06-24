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
        Schema::table('master_kends', function (Blueprint $blueprint) {
            $blueprint->string('nama_pemegang')->nullable()->after('no_polisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_kends', function (Blueprint $blueprint) {
            $blueprint->dropColumn('nama_pemegang');
        });
    }
};
