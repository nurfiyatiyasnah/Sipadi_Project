<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {

            $table->id('id_anggota');
            $table->unsignedBigInteger('id_user')->unique();
            $table->string('no_anggota', 30)->unique();
            $table->string('nik', 20)->unique();
            $table->string('nama_lengkap', 100);

            $table->string('jenis_kelamin', 10)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto', 255)->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->string('status_anggota', 20)->nullable();
            $table->text('alasan_nonaktif')->nullable();

            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
}
