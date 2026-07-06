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
        Schema::create('aturan_peminjaman', function (Blueprint $table) {
            $table->id('id_aturan_peminjaman');
            $table->string('nama_aturan', 100);
            $table->integer('lama_pinjam_hari')->nullable();
            $table->integer('maksimal_buku_per_peminjaman')->nullable();
            $table->integer('maksimal_peminjam_aktif')->nullable();
            $table->integer('masa_suspend_per_hari_terlambat')->nullable();
            $table->boolean('status_aktif')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aturan_peminjaman');
    }
};
