<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalPengambilanTable extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_pengambilan', function (Blueprint $table) {
            $table->bigInteger('id_jadwal_pengambilan')->primary();
            $table->bigInteger('id_peminjaman')->nullable();
            $table->bigInteger('id_petugas')->nullable();
            $table->date('tanggal_pengambilan')->nullable();
            $table->time('jam_mulai', 0)->nullable();
            $table->time('jam_selesai', 0)->nullable();
            $table->string('lokasi_pengambilan', 100)->nullable();
            $table->text('pesan')->nullable();
            $table->string('status_jadwal', 20)->nullable();
            $table->timestamp('dikirim_pada', 0)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('id_peminjaman');
            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
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
        Schema::dropIfExists('jadwal_pengambilan');
    }
}
