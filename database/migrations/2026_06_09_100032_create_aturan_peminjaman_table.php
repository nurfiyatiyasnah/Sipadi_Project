<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAturanPeminjamanTable extends Migration
{
    public function up(): void
    {
        Schema::create('aturan_peminjaman', function (Blueprint $table) {
            $table->bigInteger('id_aturan_peminjaman')->primary();
            $table->string('nama_aturan', 100)->nullable();
            $table->integer('lama_pinjam_hari')->nullable();
            $table->integer('maksimal_buku_per_peminjaman')->nullable();
            $table->integer('maksimal_peminjam_aktif')->nullable();
            $table->integer('masa_suspend_per_hari_terlambat')->nullable();
            $table->boolean('status_aktif')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan_peminjaman');
    }
}
