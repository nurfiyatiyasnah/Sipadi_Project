<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritaTable extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {

            $table->id('id_berita');
            $table->unsignedBigInteger('id_kategori_berita');
            $table->unsignedBigInteger('id_petugas')->nullable();
            $table->string('judul', 150);
            $table->char('slug', 225)->unique();

            $table->text('isi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('status_berita', 30)->nullable();

            $table->timestamps();

            $table->foreign('id_kategori_berita')->references('id_kategori_berita')->on('kategori_berita');
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
}
