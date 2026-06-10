<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengumumanTable extends Migration
{
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {

            $table->id('id_pengumuman');
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->string('judul', 150);

            $table->text('isi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('file_lampiran', 255)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('status_pengumuman', 20)->nullable();

            $table->timestamps();

            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
}
