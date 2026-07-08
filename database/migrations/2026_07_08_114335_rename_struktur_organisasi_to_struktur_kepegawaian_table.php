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
        Schema::rename('struktur_organisasi', 'struktur_kepegawaian');
        Schema::table('struktur_kepegawaian', function (Blueprint $table) {
            $table->renameColumn('id_struktur_organisasi', 'id_struktur_kepegawaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('struktur_kepegawaian', function (Blueprint $table) {
            $table->renameColumn('id_struktur_kepegawaian', 'id_struktur_organisasi');
        });
        Schema::rename('struktur_kepegawaian', 'struktur_organisasi');
    }
};
