<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritaTable extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->bigInteger('id_berita')->primary();
            $table->bigInteger('id_kategori_berita')->nullable();
            $table->bigInteger('id_petugas')->nullable();
            $table->string('judul', 150)->nullable();
            $table->char('slug', 225)->nullable();
            $table->text('isi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->date('tanggal_terbit')->nullable();
            $table->string('status_berita', 30)->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('slug');
            $table->foreign('id_kategori_berita')
                ->references('id_kategori_berita')
                ->on('kategori_berita')
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
        Schema::dropIfExists('berita');
    }
}
