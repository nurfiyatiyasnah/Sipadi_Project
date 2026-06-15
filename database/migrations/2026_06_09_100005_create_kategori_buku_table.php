<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriBukuTable extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_buku', function (Blueprint $table) {

            $table->id('id_kategori');
            $table->string('nama_kategori', 50)->unique();

            $table->text('deskripsi')->nullable();
            $table->timestamp('created_at', 0)->nullable();
            $table->timestamp('updated_at', 0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_buku');
    }
}
