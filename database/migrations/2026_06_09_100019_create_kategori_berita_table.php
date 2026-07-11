<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriBeritaTable extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_berita', function (Blueprint $table) {

            $table->id('id_kategori_berita');
            $table->string('nama_kategori', 50)->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_berita');
    }
}
