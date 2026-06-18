<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFasilitasTable extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {

            $table->id('id_fasilitas');
            $table->string('nama_fasilitas', 150);

            $table->string('slug', 255)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 150)->nullable();
            $table->integer('jumlah_unit')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->string('status_fasilitas', 20)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id_petugas')->on('petugas');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
}
