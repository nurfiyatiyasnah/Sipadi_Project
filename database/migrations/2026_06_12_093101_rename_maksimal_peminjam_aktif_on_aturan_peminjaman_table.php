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
        Schema::table('aturan_peminjaman', function (Blueprint $table) {
            $table->renameColumn('maksimal_peminjam_aktif', 'maksimal_peminjaman_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aturan_peminjaman', function (Blueprint $table) {
            $table->renameColumn('maksimal_peminjaman_aktif', 'maksimal_peminjam_aktif');
        });
    }
};
