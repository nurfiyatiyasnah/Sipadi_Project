<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuTable extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {

            $table->id('id_buku');
            $table->unsignedBigInteger('id_kategori');
            $table->string('kode_buku', 30)->unique();
            $table->string('isbn', 30)->unique()->nullable();
            $table->string('judul', 200);

            $table->string('penulis', 150)->nullable();
            $table->string('penerbit', 150)->nullable();
            $table->smallInteger('tahun_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_cover', 255)->nullable();
            $table->string('status_katalog', 20)->nullable();

            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_buku');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
}
