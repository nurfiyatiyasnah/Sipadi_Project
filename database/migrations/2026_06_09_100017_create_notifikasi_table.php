<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasiTable extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {

            $table->id('id_notifikasi');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_peminjaman')->nullable();
            $table->unsignedBigInteger('id_jadwal_pengambalian')->nullable();
            $table->string('judul', 100);

            $table->text('isi')->nullable();
            $table->string('jenis_notifikasi', 30)->nullable();
            $table->string('status_notifikasi', 20)->nullable();
            $table->string('status_baca', 20)->nullable();
            $table->timestamp('dikirim_pada')->nullable();

            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman');
            $table->foreign('id_jadwal_pengambalian')->references('id_jadwal_pengambilan')->on('jadwal_pengambilan');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
}
