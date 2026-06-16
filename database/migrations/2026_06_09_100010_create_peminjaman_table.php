<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamanTable extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {

            $table->id('id_peminjaman');
            $table->string('kode_peminjaman', 50)->unique();
            $table->unsignedBigInteger('id_anggota');
            $table->unsignedBigInteger('id_aturan')->nullable();
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->text('deskripsi_pengajuan')->nullable();
            $table->timestamp('tanggal_diambil')->nullable();
            $table->date('tanggal_jatuh_tempo')->nullable();
            $table->string('status_peminjaman', 30)->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('id_anggota')->references('id_anggota')->on('anggota');
            $table->foreign('id_aturan')->references('id_aturan_peminjaman')->on('aturan_peminjaman');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
}
