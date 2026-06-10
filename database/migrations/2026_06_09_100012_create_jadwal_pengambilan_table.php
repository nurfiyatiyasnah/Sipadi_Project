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
        Schema::create('jadwal_pengambilan', function (Blueprint $table) {
            $table->id('id_jadwal_pengambilan');
            $table->unsignedBigInteger('id_peminjaman')->unique();
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->date('tanggal_pengambilan');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi_pengambilan', 100)->nullable();
            $table->text('pesan')->nullable();
            $table->string('status_jadwal', 20)->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamps();

            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pengambilan');
    }
};
