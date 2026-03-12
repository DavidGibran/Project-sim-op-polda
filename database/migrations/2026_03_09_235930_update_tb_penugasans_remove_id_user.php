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
        Schema::table('tb_penugasans', function (Blueprint $table) {
            if (Schema::hasColumn('tb_penugasans', 'id_user')) {
                $table->dropForeign(['id_user']);
                $table->dropColumn('id_user');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_penugasans', function (Blueprint $table) {
            $table->foreignId('id_user')->nullable()->constrained('users','id');
        });
    }
};
