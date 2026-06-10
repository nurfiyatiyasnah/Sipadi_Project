<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanTable extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->bigInteger('id_peminjaman')->primary();
            $table->string('kode_peminjaman', 50)->nullable();
            $table->bigInteger('id_anggota')->nullable();
            $table->bigInteger('id_aturan')->nullable();
            $table->bigInteger('id_petugas')->nullable();
            $table->timestamp('tanggal_pengajuan', 0)->nullable();
            $table->text('deskripsi_pengajuan')->nullable();
            $table->timestamp('tanggal_diambil', 0)->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('status_peminjaman', 30)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('kode_peminjaman');
            $table->foreign('id_anggota')
                ->references('id_anggota')
                ->on('anggota')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_aturan')
                ->references('id_aturan_peminjaman')
                ->on('aturan_peminjaman')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_petugas')
                ->references('id_petugas')
                ->on('petugas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
}
