<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifikasiTable extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->bigInteger('id_notifikasi')->primary();
            $table->bigInteger('id_user')->nullable();
            $table->bigInteger('id_peminjaman')->nullable();
            $table->bigInteger('id_jadwal_pengambalian')->nullable();
            $table->string('judul', 100)->nullable();
            $table->text('isi')->nullable();
            $table->string('jenis_notifikasi', 30)->nullable();
            $table->string('status_notifikasi', 20)->nullable();
            $table->string('status_baca', 20)->nullable();
            $table->timestamp('dikirim_pada')->nullable();
            $table->timestamp('dibaca_pada', 0)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_peminjaman')
                ->references('id_peminjaman')
                ->on('peminjaman')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->foreign('id_jadwal_pengambalian')
                ->references('id_jadwal_pengambilan')
                ->on('jadwal_pengambilan')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
}
