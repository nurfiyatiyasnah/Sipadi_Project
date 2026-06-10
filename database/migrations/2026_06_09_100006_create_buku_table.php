<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukuTable extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->bigInteger('id_buku')->primary();
            $table->bigInteger('id_kategori')->nullable();
            $table->string('kode_buku', 30)->nullable();
            $table->string('isbn', 30)->nullable();
            $table->string('judul', 200)->nullable();
            $table->string('penulis', 150)->nullable();
            $table->string('penerbit', 150)->nullable();
            $table->smallInteger('tahun_terbit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar_cover', 255)->nullable();
            $table->string('status_katalog', 20)->nullable();
            $table->timestamp('created_at ', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('isbn');
            $table->unique('kode_buku');
            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori_buku')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
}
