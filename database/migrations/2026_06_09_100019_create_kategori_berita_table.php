<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriBeritaTable extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_berita', function (Blueprint $table) {
            $table->bigInteger('id_kategori_berita')->primary();
            $table->string('nama_kategori', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
            $table->unique('nama_kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_berita');
    }
}
