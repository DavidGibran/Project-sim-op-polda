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
        Schema::table('master_kends', function (Blueprint $table) {
            $table->string('username')->unique()->after('no_polisi')->nullable();
            $table->string('password')->after('username')->nullable();
        });

        DB::table('master_kends')->update([
            'username' => DB::raw('no_polisi'),
            'password' => bcrypt('password')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_kends', function (Blueprint $table) {
            $table->dropColumn(['username', 'password']);
        });
    }
};
